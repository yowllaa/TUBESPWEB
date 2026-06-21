@extends('layouts.dashboard')

@section('page-title', 'Detail Laporan')

@section('content')
<div class="space-y-6">
    <!-- Top Nav Action -->
    <div>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-800 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Riwayat
        </a>
    </div>

    <!-- Main Grid -->
    <div class="grid lg:grid-cols-12 gap-6">
        <!-- Left Column: Details & Photo (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Details Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <!-- Status Bar -->
                <div class="px-6 py-4 flex items-center justify-between border-b border-slate-100 
                    @if($laporan->status === 'Menunggu') bg-slate-50
                    @elseif($laporan->status === 'Diproses') bg-yellow-50/50
                    @else bg-green-50/50 @endif">
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Status Laporan:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                            @if($laporan->status === 'Menunggu') bg-slate-100 text-slate-800
                            @elseif($laporan->status === 'Diproses') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800 @endif">
                            {{ $laporan->status }}
                        </span>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">
                        Dikirim: {{ $laporan->created_at->format('d M Y, H:i') }}
                    </span>
                </div>

                <!-- Info Block -->
                <div class="p-6 space-y-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase bg-blue-50 text-blue-800">
                        {{ $laporan->kategoriPelaporan->nama_kategori }}
                    </span>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 leading-tight">
                        {{ $laporan->judul }}
                    </h2>
                    
                    <div class="space-y-1">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Deskripsi Laporan:</h4>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                            {{ $laporan->deskripsi }}
                        </p>
                    </div>

                    <div class="space-y-1 pt-3 border-t border-slate-100">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lokasi Kejadian:</h4>
                        <p class="text-slate-700 text-sm font-semibold flex items-start">
                            <svg class="w-4 h-4 mr-1 text-[#DC2626] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $laporan->lokasi }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Photo Evidence Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-3">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Foto Bukti Kejadian</h3>
                <div class="rounded-xl overflow-hidden bg-slate-50 border border-slate-100">
                    <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto Bukti" class="w-full h-auto max-h-[500px] object-contain mx-auto">
                </div>
            </div>
        </div>

        <!-- Right Column: Map & Follow-up History (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Map Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Koordinat Peta</h3>
                </div>
                <div class="h-64 w-full" id="show-map"></div>
                <div class="p-3 text-center bg-slate-50 border-t border-slate-100 text-[10px] text-slate-500 font-mono">
                    {{ $laporan->latitude }}, {{ $laporan->longitude }}
                </div>
            </div>

            <!-- Follow-up (Tindak Lanjut) Timeline Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Perkembangan Kasus</h3>
                </div>

                <div class="p-6">
                    @if($laporan->tindakLanjut->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="-mb-8">
                                @foreach($laporan->tindakLanjut as $index => $tl)
                                    <li>
                                        <div class="relative pb-8">
                                            @if($index !== $laporan->tindakLanjut->count() - 1)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                            @endif
                                            
                                            <div class="relative flex space-x-3">
                                                <!-- Icon Badge -->
                                                <div>
                                                    <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white
                                                        @if($tl->status === 'Selesai') bg-green-500 text-white
                                                        @else bg-yellow-500 text-white @endif">
                                                        @if($tl->status === 'Selesai')
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                        @else
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3"/></svg>
                                                        @endif
                                                    </span>
                                                </div>

                                                <!-- Content -->
                                                <div class="flex-1 min-w-0 pt-1.5">
                                                    <p class="text-sm font-bold text-slate-800">
                                                        {{ $tl->instansi->nama_instansi }}
                                                    </p>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700 mt-0.5">
                                                        Status: {{ $tl->status }}
                                                    </span>
                                                    <div class="mt-2 text-sm text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-150 whitespace-pre-line">
                                                        {{ $tl->catatan }}
                                                    </div>
                                                    <p class="text-[10px] text-slate-400 mt-1.5">
                                                        Diperbarui: {{ $tl->updated_at->format('d M Y, H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <!-- No progress yet -->
                        <div class="text-center py-6 space-y-2 text-slate-400">
                            <svg class="mx-auto h-8 w-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs font-semibold">Menunggu Disposisi Instansi</p>
                            <p class="text-[10px] text-slate-400">Admin atau instansi yang bersangkutan sedang meninjau laporan Anda.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const lat = {{ $laporan->latitude }};
        const lng = {{ $laporan->longitude }};

        // Initialize Map
        const map = L.map('show-map').setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add Marker
        const redIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        L.marker([lat, lng], { icon: redIcon })
            .bindPopup('<b>Titik Kejadian</b><br>{{ $laporan->judul }}')
            .addTo(map)
            .openPopup();
    });
</script>
@endsection
