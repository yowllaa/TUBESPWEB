<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgencyController;
use Illuminate\Support\Facades\Route;

// Public Guest Routes
Route::get('/', [GuestController::class, 'index'])->name('welcome');

// Role-Based Redirection Dashboard
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'instansi') {
        return redirect()->route('agency.dashboard');
    }
    
    // Masyarakat Dashboard stats & latest reports
    $totalReports = \App\Models\Laporan::where('user_id', $user->id)->count();
    $totalCompleted = \App\Models\Laporan::where('user_id', $user->id)->where('status', 'Selesai')->count();
    $totalProcessing = \App\Models\Laporan::where('user_id', $user->id)->where('status', 'Diproses')->count();
    $latestReports = \App\Models\Laporan::where('user_id', $user->id)->with('kategoriPelaporan')->latest()->take(5)->get();
    $emergencyCategories = \App\Models\KategoriDarurat::all();

    return view('dashboard', compact('totalReports', 'totalCompleted', 'totalProcessing', 'latestReports', 'emergencyCategories'));
})->middleware(['auth'])->name('dashboard');

// Auth Profile (Masyarakat, Admin, Instansi)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Masyarakat Routes (Layanan Darurat & Pelaporan)
Route::middleware(['auth', 'role:masyarakat'])->group(function () {
    // Emergency Call (Quick Emergency)
    Route::get('/darurat', [EmergencyController::class, 'index'])->name('emergency.index');
    Route::post('/darurat/cari', [EmergencyController::class, 'search'])->name('emergency.search');

    // Public Issue Reporting
    Route::resource('reports', ReportController::class)->only(['index', 'create', 'store', 'show']);
});

// Instansi Routes (Dashboard & Follow Up Laporan)
Route::middleware(['auth', 'role:instansi'])->prefix('agency')->name('agency.')->group(function () {
    Route::get('/dashboard', [AgencyController::class, 'index'])->name('dashboard');
    Route::get('/reports/{laporan}', [AgencyController::class, 'showReport'])->name('reports.show');
    Route::post('/reports/{laporan}/process', [AgencyController::class, 'processReport'])->name('reports.process');
    Route::post('/reports/{laporan}/complete', [AgencyController::class, 'completeReport'])->name('reports.complete');
    Route::get('/profile', [AgencyController::class, 'editProfile'])->name('profile');
    Route::patch('/profile', [AgencyController::class, 'updateProfile'])->name('profile.update');
});

// Admin Routes (Full Management & Statistics)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Stats & Overview
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // User Management CRUD
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::patch('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');

    // Agency Management CRUD
    Route::get('/agencies', [AdminController::class, 'agencies'])->name('agencies.index');
    Route::post('/agencies', [AdminController::class, 'storeAgency'])->name('agencies.store');
    Route::patch('/agencies/{agency}', [AdminController::class, 'updateAgency'])->name('agencies.update');
    Route::delete('/agencies/{agency}', [AdminController::class, 'deleteAgency'])->name('agencies.delete');

    // Category CRUD
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories/emergency', [AdminController::class, 'storeEmergencyCategory'])->name('categories.emergency.store');
    Route::delete('/categories/emergency/{category}', [AdminController::class, 'deleteEmergencyCategory'])->name('categories.emergency.delete');
    Route::post('/categories/reporting', [AdminController::class, 'storeReportingCategory'])->name('categories.reporting.store');
    Route::delete('/categories/reporting/{category}', [AdminController::class, 'deleteReportingCategory'])->name('categories.reporting.delete');

    // Reports Monitoring
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports.index');
    Route::delete('/reports/{report}', [AdminController::class, 'deleteReport'])->name('reports.delete');
});

require __DIR__.'/auth.php';
