<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Laporan;
use App\Models\TindakLanjut;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    private function getAgency()
    {
        $agency = auth()->user()->instansi;
        if (!$agency) {
            abort(404, 'Data instansi Anda tidak ditemukan. Hubungi Administrator.');
        }
        return $agency;
    }

    public function index()
    {
        $agency = $this->getAgency();

        // Get reports that are handled by this agency
        $handledReports = Laporan::whereHas('tindakLanjut', function ($query) use ($agency) {
            $query->where('instansi_id', $agency->id);
        })->with('kategoriPelaporan')->get();

        // Count stats
        $totalHandled = $handledReports->count();
        $totalCompleted = $handledReports->where('status', 'Selesai')->count();
        $totalProcessing = $handledReports->where('status', 'Diproses')->count();

        // Get incoming reports (still 'Menunggu' status)
        $incomingReports = Laporan::where('status', 'Menunggu')
            ->with('kategoriPelaporan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('agency.dashboard', compact('agency', 'totalHandled', 'totalCompleted', 'totalProcessing', 'incomingReports', 'handledReports'));
    }

    public function showReport(Laporan $laporan)
    {
        $agency = $this->getAgency();
        $laporan->load(['kategoriPelaporan', 'user', 'tindakLanjut.instansi']);

        // Check if report has tindak lanjut by this agency
        $currentFollowUp = $laporan->tindakLanjut->where('instansi_id', $agency->id)->first();

        return view('agency.reports.show', compact('laporan', 'currentFollowUp', 'agency'));
    }

    public function processReport(Laporan $laporan)
    {
        $agency = $this->getAgency();

        if ($laporan->status !== 'Menunggu') {
            return back()->with('error', 'Laporan ini sudah diproses atau diselesaikan oleh pihak lain.');
        }

        // Update report status
        $laporan->update(['status' => 'Diproses']);

        // Create tindak lanjut record
        TindakLanjut::create([
            'laporan_id' => $laporan->id,
            'instansi_id' => $agency->id,
            'catatan' => 'Laporan sedang dalam proses penanganan oleh ' . $agency->nama_instansi,
            'status' => 'Diproses',
        ]);

        return redirect()->route('agency.reports.show', $laporan->id)->with('success', 'Laporan berhasil ditandai sebagai Diproses.');
    }

    public function completeReport(Request $request, Laporan $laporan)
    {
        $agency = $this->getAgency();

        $request->validate([
            'catatan' => 'required|string',
        ]);

        // Find the tindak lanjut record
        $followUp = TindakLanjut::where('laporan_id', $laporan->id)
            ->where('instansi_id', $agency->id)
            ->first();

        if (!$followUp) {
            return back()->with('error', 'Tindak lanjut tidak ditemukan.');
        }

        // Update report status
        $laporan->update(['status' => 'Selesai']);

        // Update tindak lanjut
        $followUp->update([
            'catatan' => $request->catatan,
            'status' => 'Selesai',
        ]);

        return redirect()->route('agency.reports.show', $laporan->id)->with('success', 'Laporan berhasil diselesaikan.');
    }

    public function editProfile()
    {
        $agency = $this->getAgency();
        return view('agency.profile', compact('agency'));
    }

    public function updateProfile(Request $request)
    {
        $agency = $this->getAgency();

        $request->validate([
            'nama_instansi' => 'required|string|max:255',
            'nomor_telepon' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'alamat' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'wilayah' => 'required|string|max:255',
        ]);

        $agency->update($request->only([
            'nama_instansi',
            'nomor_telepon',
            'email',
            'alamat',
            'latitude',
            'longitude',
            'wilayah',
        ]));

        return redirect()->route('agency.profile')->with('success', 'Profil instansi berhasil diperbarui.');
    }
}
