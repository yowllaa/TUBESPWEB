<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\KategoriDarurat;
use App\Services\DistanceService;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    public function index()
    {
        $kategoriDarurat = KategoriDarurat::all();
        return view('emergency.index', compact('kategoriDarurat'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'kategori' => 'required|string',
        ]);

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;
        $kategori = $request->kategori;

        // Map kategori darurat to kategori_instansi
        $kategoriInstansi = [$kategori];

        if ($kategori === 'Kecelakaan') {
            // Kecelakaan can involve Medis or Gangguan Keamanan
            $kategoriInstansi = ['Darurat Medis', 'Gangguan Keamanan', 'Kecelakaan'];
        }

        $agencies = Instansi::whereIn('kategori_instansi', $kategoriInstansi)->get();

        $results = [];
        foreach ($agencies as $agency) {
            $distance = DistanceService::calculateDistance($lat, $lng, (float) $agency->latitude, (float) $agency->longitude);
            
            $results[] = [
                'id' => $agency->id,
                'nama_instansi' => $agency->nama_instansi,
                'kategori_instansi' => $agency->kategori_instansi,
                'alamat' => $agency->alamat,
                'nomor_telepon' => $agency->nomor_telepon,
                'email' => $agency->email,
                'latitude' => (float) $agency->latitude,
                'longitude' => (float) $agency->longitude,
                'wilayah' => $agency->wilayah,
                'distance' => $distance,
            ];
        }

        // Sort by distance ascending
        usort($results, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        return response()->json($results);
    }
}
