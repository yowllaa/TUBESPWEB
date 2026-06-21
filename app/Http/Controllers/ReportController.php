<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanRequest;
use App\Models\KategoriPelaporan;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Laporan::where('user_id', auth()->id())
            ->with('kategoriPelaporan')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $categories = KategoriPelaporan::all();
        return view('reports.create', compact('categories'));
    }

    public function store(StoreLaporanRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('laporan', 'public');
            $validated['foto'] = $path;
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'Menunggu';

        Laporan::create($validated);

        return redirect()->route('reports.index')->with('success', 'Laporan berhasil dikirim dan sedang menunggu tindak lanjut.');
    }

    public function show(Laporan $laporan)
    {
        // Ensure user owns this report
        if ($laporan->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $laporan->load(['kategoriPelaporan', 'tindakLanjut.instansi']);

        return view('reports.show', compact('laporan'));
    }
}
