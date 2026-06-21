@extends('layouts.dashboard')

@section('page-title', 'Riwayat Laporan Anda')

@section('content')
<div class="space-y-6">
    <!-- Header with Create Action -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Pengaduan Anda</h2>
            <p class="text-xs text-slate-400">Pantau proses penanganan pengaduan fasilitas publik yang Anda kirimkan.</p>
        </div>
        <a href="{{ route('reports.create') }}" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md hover:shadow-lg transition">
            + Buat Laporan Baru
        </a>
    </div>

    <!-- Main List Container -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="divide-y divide-slate-100">
            @forelse($reports as $rep)
                <div class="p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 hover:bg-slate-50 transition duration-150">
                    
                    <!-- Left: Details -->
                    <div class="flex items-start space-x-4">
                        <!-- Thumbnail photo of report -->
                        <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 flex-shrink-0">
                            <img src="{{ asset('storage/' . $rep->foto) }}" alt="Foto Laporan" class="w-full h-full object-cover">
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase bg-blue-50 text-blue-800">
                                    {{ $rep->kategoriPelaporan->nama_kategori }}
                                </span>
                                <span class="text-[10px] font-semibold text-slate-400">
                                    {{ $rep->created_at->diffForHumans() }} ({{ $rep->created_at->format('d M Y') }})
                                </span>
                            </div>
                            <h3 class="font-bold text-slate-800 text-base md:text-lg leading-tight hover:text-blue-600">
                                <a href="{{ route('reports.show', $rep->id) }}">{{ $rep->judul }}</a>
                            </h3>
                            <p class="text-xs text-slate-500 line-clamp-1 max-w-xl">{{ $rep->deskripsi }}</p>
                            <p class="text-xs text-slate-400 flex items-center mt-1">
                                <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $rep->lokasi }}
                            </p>
                        </div>
                    </div>

                    <!-- Right: Status Badge & View Button -->
                    <div class="flex items-center space-x-4 w-full md:w-auto justify-between md:justify-end border-t md:border-0 pt-4 md:pt-0 border-slate-100">
                        <div class="text-left md:text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                @if($rep->status === 'Menunggu') bg-slate-100 text-slate-800
                                @elseif($rep->status === 'Diproses') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800 @endif">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 
                                    @if($rep->status === 'Menunggu') bg-slate-400
                                    @elseif($rep->status === 'Diproses') bg-yellow-500
                                    @else bg-green-500 @endif"></span>
                                {{ $rep->status }}
                            </span>
                        </div>

                        <a href="{{ route('reports.show', $rep->id) }}" class="px-4 py-2 border border-slate-200 hover:border-slate-300 rounded-xl text-xs font-bold text-slate-600 bg-white transition hover:shadow-sm">
                            Lihat Detail
                        </a>
                    </div>

                </div>
            @empty
                <div class="p-12 text-center text-slate-400">
                    <svg class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="font-bold text-slate-600">Belum Ada Riwayat Laporan</p>
                    <p class="text-xs text-slate-400 mt-1 mb-4">Semua laporan keluhan infrastruktur publik Anda akan muncul di sini.</p>
                    <a href="{{ route('reports.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition">
                        Buat Laporan Sekarang
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
