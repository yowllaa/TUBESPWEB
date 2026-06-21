@extends('layouts.dashboard')

@section('page-title', 'Detail Penanganan Laporan')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <div>
        <a href="{{ route('agency.dashboard') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-800 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    <!-- Main Grid -->
    <div class="grid lg:grid-cols-12 gap-6">
        <!-- Left Column: Details & Photo (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Basic Details Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 flex items-center justify-between border-b border-slate-100 bg-slate-50">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase bg-blue-50 text-blue-800">
                        {{ $laporan->kategoriPelaporan->nama_kategori }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                        @if($laporan->status === 'Menunggu') bg-slate-100 text-slate-800
                        @elseif($laporan->status === 'Diproses') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800 @endif">
                        {{ $laporan->status }}
                    </span>
                </div>

                <div class="p-6 space-y-4">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-800 leading-tight">
                        {{ $laporan->judul }}
                    </h2>
                    
                    <div class="flex items-center space-x-3 text-xs text-slate-400">
                        <p>Pelapor: <span class="font-bold text-slate-600">{{ $laporan->user->name }}</span></p>
                        <span>•</span>
                        <p>Telepon: <span class="font-bold text-slate-600">{{ $laporan->user->phone ?: '-' }}</span></p>
                        <span>•</span>
                        <p>{{ $laporan->created_at->format('d M Y, H:i') }}</p>
                    </div>

                    <div class="space-y-1 pt-3 border-t border-slate-150">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Deskripsi Keluhan:</h4>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed whitespace-pre-line">
                            {{ $laporan->deskripsi }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Alamat Kejadian:</h4>
                        <p class="text-slate-700 text-sm font-semibold flex items-start">
                            <svg class="w-4 h-4 mr-1 text-[#DC2626] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $laporan->lokasi }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Image Bukti -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-3">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Bukti Foto</h3>
                <div class="rounded-xl overflow-hidden bg-slate-50 border border-slate-100">
                    <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto Bukti" class="w-full h-auto max-h-[500px] object-contain mx-auto">
                </div>
            </div>
        </div>

        <!-- Right Column: Map & Action Center (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <!-- Map Card -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Titik Koordinat Peta</h3>
                </div>
                <div class="h-64 w-full" id="show-map"></div>
                <div class="p-3 text-center bg-slate-50 border-t border-slate-100 text-[10px] text-slate-500 font-mono">
                    {{ $laporan->latitude }}, {{ $laporan->longitude }}
                </div>
            </div>

            <!-- Action Panel for Agency -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-6 space-y-6">
                <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider border-b border-slate-100 pb-3">Pusat Tindak Lanjut Instansi</h3>

                @if($laporan->status === 'Menunggu')
                    <!-- Case 1: Unassigned/Pending -> Let them process it -->
                    <div class="space-y-4">
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Laporan ini belum ditindaklanjuti oleh instansi manapun. Jika ini masuk dalam lingkup tugas instansi Anda, tekan tombol di bawah untuk mulai memproses.
                        </p>
                        <form action="{{ route('agency.reports.process', $laporan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 bg-[#2563EB] hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md transition duration-200">
                                Proses Laporan Ini
                            </button>
                        </form>
                    </div>

                @elseif($laporan->status === 'Diproses')
                    <!-- Case 2: In Progress -->
                    @if($currentFollowUp)
                        <!-- If processed by THIS agency, show completion form -->
                        <form action="{{ route('agency.reports.complete', $laporan->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-2">
                                <label for="catatan" class="text-sm font-bold text-slate-700">Catatan Penyelesaian Laporan</label>
                                <textarea id="catatan" name="catatan" rows="4" required placeholder="Jelaskan tindakan yang telah dilakukan (misal: Perbaikan lampu jalan telah selesai dikerjakan pada tanggal...)"
                                          class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>
                            
                            <button type="submit" class="w-full py-3 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-xl shadow-md transition duration-200">
                                Selesaikan Laporan Ini
                            </button>
                        </form>
                    @else
                        <!-- Processed by another agency -->
                        <div class="text-center py-4 text-slate-400 space-y-2">
                            <svg class="mx-auto h-8 w-8 text-slate-350" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <p class="text-xs font-semibold">Kasus Sedang Ditangani Instansi Lain</p>
                        </div>
                    @endif

                @else
                    <!-- Case 3: Completed -->
                    <div class="space-y-4">
                        <div class="p-4 bg-green-50 border border-green-200 rounded-xl space-y-2">
                            <div class="flex items-center space-x-1 text-green-800 font-bold text-sm">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Kasus Telah Selesai</span>
                            </div>
                            <p class="text-xs text-green-700">Telah ditindaklanjuti dengan sukses oleh instansi pelaksana.</p>
                        </div>

                        <!-- Details of the follow up -->
                        @foreach($laporan->tindakLanjut as $tl)
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-2 text-xs">
                                <p class="font-bold text-slate-700">Ditangani Oleh: <span class="text-slate-800">{{ $tl->instansi->nama_instansi }}</span></p>
                                <p class="text-slate-650 leading-relaxed"><span class="font-semibold text-slate-500">Catatan:</span><br>{{ $tl->catatan }}</p>
                                <p class="text-[10px] text-slate-400">Tanggal update: {{ $tl->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
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
