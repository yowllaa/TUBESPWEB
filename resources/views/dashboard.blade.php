@extends('layouts.dashboard')

@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Banner -->
    <div class="p-6 bg-gradient-to-r from-red-600 to-blue-600 rounded-2xl text-white shadow-lg">
        <div class="max-w-3xl">
            <h2 class="text-2xl sm:text-3xl font-bold">Halo!</h2>
            <p class="mt-2 text-sm sm:text-base text-red-50">
                Selamat datang di platform SIP-DARURAT. Di sini Anda dapat memanggil pos penyelamatan terdekat atau melaporkan permasalahan infrastruktur publik.
            </p>
        </div>
    </div>

    <!-- Quick Emergency Section -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-red-100 rounded-lg text-red-600 animate-pulse">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">TINDAKAN DARURAT CEPAT</h3>
                <p class="text-xs text-slate-400">Pilih jenis kejadian di bawah ini untuk mencari bantuan pos instansi terdekat.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($emergencyCategories as $cat)
                <a href="{{ route('emergency.index', ['kategori' => $cat->nama_kategori]) }}" 
                   class="p-4 bg-red-50/50 hover:bg-red-50 border border-red-200 hover:border-red-500 rounded-2xl text-center flex flex-col items-center justify-center space-y-3 group transition duration-200 hover:shadow-sm">
                    <div class="p-3 bg-white text-[#DC2626] rounded-xl group-hover:scale-105 transition shadow-sm border border-red-100">
                        @if($cat->nama_kategori === 'Kebakaran')
                            <!-- Fire icon -->
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        @elseif($cat->nama_kategori === 'Kecelakaan')
                            <!-- Accident icon -->
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        @elseif($cat->nama_kategori === 'Darurat Medis')
                            <!-- Medical icon -->
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        @elseif($cat->nama_kategori === 'Gangguan Keamanan')
                            <!-- Police Shield icon -->
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        @else
                            <!-- Wave/Bencana icon -->
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                        @endif
                    </div>
                    <span class="text-sm font-bold text-slate-800">{{ $cat->nama_kategori }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Stats and Action Grid -->
    <div class="grid md:grid-cols-3 gap-6">
        <!-- Stats -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Laporan Anda</span>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalReports }}</h3>
            </div>
            <div class="p-3 bg-blue-50 text-[#2563EB] rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Laporan Diproses</span>
                <h3 class="text-3xl font-extrabold text-yellow-600">{{ $totalProcessing }}</h3>
            </div>
            <div class="p-3 bg-yellow-50 text-yellow-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Laporan Selesai</span>
                <h3 class="text-3xl font-extrabold text-green-600">{{ $totalCompleted }}</h3>
            </div>
            <div class="p-3 bg-green-50 text-green-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Reports History Summary -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Laporan Terbaru Anda</h3>
                <p class="text-xs text-slate-400">Daftar 5 pengaduan non-darurat terakhir yang Anda kirimkan.</p>
            </div>
            <a href="{{ route('reports.create') }}" class="px-4 py-2 bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm hover:shadow-md transition">
                + Buat Laporan
            </a>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($latestReports as $rep)
                <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center hover:bg-slate-50 transition duration-150 gap-4">
                    <div class="space-y-1">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700">
                            {{ $rep->kategoriPelaporan->nama_kategori }}
                        </span>
                        <h4 class="font-bold text-slate-800 text-sm sm:text-base">{{ $rep->judul }}</h4>
                        <p class="text-xs text-slate-400 flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $rep->lokasi }}
                        </p>
                    </div>

                    <div class="flex items-center space-x-3 w-full sm:w-auto justify-between sm:justify-end">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold 
                            @if($rep->status === 'Menunggu') bg-slate-100 text-slate-800
                            @elseif($rep->status === 'Diproses') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ $rep->status }}
                        </span>
                        
                        <a href="{{ route('reports.show', $rep->id) }}" class="inline-flex items-center px-3 py-1.5 border border-slate-200 hover:border-slate-300 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-800 bg-white transition shadow-sm">
                            Detail Laporan
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-slate-400">
                    Anda belum pernah membuat laporan. 
                    <a href="{{ route('reports.create') }}" class="text-[#2563EB] hover:underline font-bold">Buat laporan sekarang</a>.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
