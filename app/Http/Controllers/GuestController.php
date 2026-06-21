<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\KategoriDarurat;
use App\Models\KategoriPelaporan;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        $totalPengguna = User::where('role', 'masyarakat')->count();
        $totalInstansi = Instansi::count();
        $totalLaporan = Laporan::count();
        $laporanSelesai = Laporan::where('status', 'Selesai')->count();

        $instansiList = Instansi::orderBy('nama_instansi')->get();
        $kategoriDarurat = KategoriDarurat::all();
        $kategoriPelaporan = KategoriPelaporan::all();

        return view('welcome', compact(
            'totalPengguna',
            'totalInstansi',
            'totalLaporan',
            'laporanSelesai',
            'instansiList',
            'kategoriDarurat',
            'kategoriPelaporan'
        ));
    }
}
