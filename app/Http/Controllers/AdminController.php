<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\KategoriDarurat;
use App\Models\KategoriPelaporan;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPengguna = User::where('role', 'masyarakat')->count();
        $totalInstansi = Instansi::count();
        $totalLaporan = Laporan::count();
        $laporanBulanIni = Laporan::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // 1. Chart Laporan Per Bulan (Database-agnostic using Eloquent + Collection)
        $reports = Laporan::select('created_at')->get();
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $chartBulanData = array_fill_keys($months, 0);

        foreach ($reports as $r) {
            $monthName = $r->created_at->format('M');
            if (array_key_exists($monthName, $chartBulanData)) {
                $chartBulanData[$monthName]++;
            }
        }

        // 2. Chart Laporan Per Kategori
        $categoriesData = Laporan::with('kategoriPelaporan')
            ->get()
            ->groupBy('kategoriPelaporan.nama_kategori')
            ->map(fn($group) => $group->count())
            ->toArray();

        return view('admin.dashboard', compact(
            'totalPengguna',
            'totalInstansi',
            'totalLaporan',
            'laporanBulanIni',
            'chartBulanData',
            'categoriesData'
        ));
    }

    // --- MANAGE USERS ---
    public function users()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,masyarakat,instansi',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,masyarakat,instansi',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus (soft delete).');
    }

    // --- MANAGE INSTANSI ---
    public function agencies()
    {
        $agencies = Instansi::with('user')->orderBy('nama_instansi')->get();
        $instansiUsers = User::where('role', 'instansi')->whereDoesntHave('instansi')->get();
        $allInstansiUsers = User::where('role', 'instansi')->get();

        return view('admin.agencies.index', compact('agencies', 'instansiUsers', 'allInstansiUsers'));
    }

    public function storeAgency(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id|unique:instansi,user_id',
            'nama_instansi' => 'required|string|max:255',
            'kategori_instansi' => 'required|string',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'wilayah' => 'required|string|max:255',
        ]);

        Instansi::create($request->all());

        return back()->with('success', 'Instansi berhasil ditambahkan.');
    }

    public function updateAgency(Request $request, Instansi $agency)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id|unique:instansi,user_id,' . $agency->id,
            'nama_instansi' => 'required|string|max:255',
            'kategori_instansi' => 'required|string',
            'alamat' => 'required|string',
            'nomor_telepon' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'wilayah' => 'required|string|max:255',
        ]);

        $agency->update($request->all());

        return back()->with('success', 'Instansi berhasil diperbarui.');
    }

    public function deleteAgency(Instansi $agency)
    {
        $agency->delete();
        return back()->with('success', 'Instansi berhasil dihapus (soft delete).');
    }

    // --- MANAGE CATEGORIES ---
    public function categories()
    {
        $emergencyCategories = KategoriDarurat::orderBy('nama_kategori')->get();
        $reportingCategories = KategoriPelaporan::orderBy('nama_kategori')->get();

        return view('admin.categories.index', compact('emergencyCategories', 'reportingCategories'));
    }

    public function storeEmergencyCategory(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|unique:kategori_darurat,nama_kategori']);
        KategoriDarurat::create(['nama_kategori' => $request->nama_kategori]);
        return back()->with('success', 'Kategori darurat berhasil ditambahkan.');
    }

    public function deleteEmergencyCategory(KategoriDarurat $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori darurat berhasil dihapus.');
    }

    public function storeReportingCategory(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|unique:kategori_pelaporan,nama_kategori']);
        KategoriPelaporan::create(['nama_kategori' => $request->nama_kategori]);
        return back()->with('success', 'Kategori pelaporan berhasil ditambahkan.');
    }

    public function deleteReportingCategory(KategoriPelaporan $category)
    {
        $category->delete();
        return back()->with('success', 'Kategori pelaporan berhasil dihapus.');
    }

    // --- MANAGE REPORTS ---
    public function reports()
    {
        $reports = Laporan::with(['kategoriPelaporan', 'user', 'tindakLanjut.instansi'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reports.index', compact('reports'));
    }

    public function deleteReport(Laporan $report)
    {
        $report->delete();
        return back()->with('success', 'Laporan berhasil dihapus (soft delete).');
    }
}
