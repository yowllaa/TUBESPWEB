@extends('layouts.dashboard')

@section('page-title', 'Dashboard Instansi')

@section('content')
<div class="space-y-8">
    <!-- Header Card -->
    <div class="p-6 bg-slate-900 rounded-2xl text-white shadow-lg border border-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold">{{ $agency->nama_instansi }}</h2>
            <p class="text-sm text-slate-400 mt-1">
                Kategori Penanganan: <span class="px-2 py-0.5 rounded bg-red-950 text-red-300 font-semibold text-xs border border-red-900">{{ $agency->kategori_instansi }}</span> 
                | Wilayah Kerja: <span class="font-semibold text-slate-200">{{ $agency->wilayah }}</span>
            </p>
        </div>
        <a href="{{ route('agency.profile') }}" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-slate-700 transition">
            Ubah Lokasi & Kontak
        </a>
    </div>

    <!-- Stats Panel -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Laporan Ditangani</span>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalHandled }}</h3>
            </div>
            <div class="p-3 bg-blue-50 text-[#2563EB] rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Sedang Diproses</span>
                <h3 class="text-3xl font-extrabold text-yellow-600">{{ $totalProcessing }}</h3>
            </div>
            <div class="p-3 bg-yellow-50 text-yellow-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Selesai Ditangani</span>
                <h3 class="text-3xl font-extrabold text-green-600">{{ $totalCompleted }}</h3>
            </div>
            <div class="p-3 bg-green-50 text-green-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Split view for Incoming (Pending) and handled reports -->
    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Left Column: Incoming Reports (Waiting for follow up) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider flex items-center">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-yellow-500 mr-2 animate-pulse"></span>
                    Laporan Menunggu Tindak Lanjut
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Daftar laporan masyarakat umum yang membutuhkan penanganan.</p>
            </div>

            <div class="divide-y divide-slate-150 overflow-y-auto max-h-[500px]">
                @forelse($incomingReports as $rep)
                    <div class="p-5 flex items-center justify-between hover:bg-slate-50 transition duration-150">
                        <div class="flex items-center space-x-3 truncate">
                            <img src="{{ asset('storage/' . $rep->foto) }}" alt="Bukti" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border">
                            <div class="truncate">
                                <h4 class="font-bold text-slate-800 text-sm truncate">{{ $rep->judul }}</h4>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-blue-50 text-blue-800 mt-1">
                                    {{ $rep->kategoriPelaporan->nama_kategori }}
                                </span>
                                <span class="text-[10px] text-slate-400 block sm:inline sm:ml-2">{{ $rep->created_at->diffForHumans() }}</span>
                            </div>
                        </div>

                        <a href="{{ route('agency.reports.show', $rep->id) }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-sm transition">
                            Tinjau
                        </a>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        Tidak ada laporan baru saat ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Handled Reports (In progress or completed by this agency) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider flex items-center">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#2563EB] mr-2"></span>
                    Laporan Yang Ditangani Instansi Anda
                </h3>
                <p class="text-xs text-slate-400 mt-0.5">Daftar kasus yang sedang atau telah diselesaikan oleh instansi Anda.</p>
            </div>

            <div class="divide-y divide-slate-150 overflow-y-auto max-h-[500px]">
                @forelse($handledReports as $rep)
                    <div class="p-5 flex items-center justify-between hover:bg-slate-50 transition duration-150">
                        <div class="flex items-center space-x-3 truncate">
                            <img src="{{ asset('storage/' . $rep->foto) }}" alt="Bukti" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 border">
                            <div class="truncate">
                                <h4 class="font-bold text-slate-800 text-sm truncate">{{ $rep->judul }}</h4>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-blue-50 text-blue-800">
                                        {{ $rep->kategoriPelaporan->nama_kategori }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold 
                                        @if($rep->status === 'Diproses') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                                        {{ $rep->status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('agency.reports.show', $rep->id) }}" class="px-3 py-1.5 border border-slate-200 hover:border-slate-350 rounded-lg text-xs font-semibold text-slate-600 hover:text-slate-800 bg-white transition shadow-sm">
                            Detail
                        </a>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        Instansi Anda belum menangani laporan apapun.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
