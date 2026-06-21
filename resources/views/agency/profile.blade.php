@extends('layouts.dashboard')

@section('page-title', 'Ubah Profil Instansi')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="profileMap()">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-slate-800">Detail Informasi Kantor Pos/Instansi</h3>
                <p class="text-xs text-slate-400">Sesuaikan informasi kontak dan titik lokasi pos Anda agar masyarakat dapat menghubungi Anda.</p>
            </div>
            <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full font-bold text-xs">
                {{ $agency->kategori_instansi }}
            </span>
        </div>

        <form action="{{ route('agency.profile.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PATCH')

            <!-- Grid Nama & Kontak -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Nama Instansi -->
                <div class="space-y-1.5">
                    <label for="nama_instansi" class="text-sm font-bold text-slate-700">Nama Pos / Instansi</label>
                    <input type="text" id="nama_instansi" name="nama_instansi" value="{{ old('nama_instansi', $agency->nama_instansi) }}" required
                           class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('nama_instansi') border-red-500 @enderror">
                    @error('nama_instansi')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor Telepon Kontak -->
                <div class="space-y-1.5">
                    <label for="nomor_telepon" class="text-sm font-bold text-slate-700">Nomor Telepon Darurat / Kontak Kantor</label>
                    <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $agency->nomor_telepon) }}" required
                           class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('nomor_telepon') border-red-500 @enderror">
                    @error('nomor_telepon')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Grid Email & Wilayah -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Email -->
                <div class="space-y-1.5">
                    <label for="email" class="text-sm font-bold text-slate-700">Email Resmi Instansi</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $agency->email) }}"
                           class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Wilayah Kerja -->
                <div class="space-y-1.5">
                    <label for="wilayah" class="text-sm font-bold text-slate-700">Wilayah Kerja / Yurisdiksi</label>
                    <input type="text" id="wilayah" name="wilayah" value="{{ old('wilayah', $agency->wilayah) }}" required
                           class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('wilayah') border-red-500 @enderror">
                    @error('wilayah')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Alamat Kantor -->
            <div class="space-y-1.5">
                <label for="alamat" class="text-sm font-bold text-slate-700">Alamat Fisik Kantor Pos</label>
                <textarea id="alamat" name="alamat" rows="3" required
                          class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('alamat') border-red-500 @enderror">{{ old('alamat', $agency->alamat) }}</textarea>
                @error('alamat')
                    <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Peta Lokasi Pos Kedaruratan (Latitude & Longitude) -->
            <div class="space-y-3">
                <label class="text-sm font-bold text-slate-700">Tentukan Lokasi Pos di Peta (Geser pin marker untuk merubah posisi)</label>
                
                <!-- Hidden inputs for coordinates -->
                <input type="hidden" id="latitude" name="latitude" x-model="latitude">
                <input type="hidden" id="longitude" name="longitude" x-model="longitude">

                <!-- Map View -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-inner">
                    <div id="profile-map" class="w-full h-80 z-0"></div>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl text-xs text-slate-500 font-mono">
                    Koordinat Lokasi Pos: <span class="font-bold text-slate-800" x-text="latitude + ', ' + longitude"></span>
                </div>
            </div>

            <!-- Submit Panel -->
            <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
                <a href="{{ route('agency.dashboard') }}" class="px-5 py-3 border border-slate-200 rounded-xl text-sm font-bold text-slate-650 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function profileMap() {
        return {
            latitude: {{ old('latitude', $agency->latitude) }},
            longitude: {{ old('longitude', $agency->longitude) }},
            map: null,
            marker: null,

            init() {
                // Initialize Map
                this.map = L.map('profile-map').setView([this.latitude, this.longitude], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(this.map);

                // Add draggable marker
                this.marker = L.marker([this.latitude, this.longitude], { draggable: true }).addTo(this.map);
                this.marker.bindPopup('<b>Pos Instansi Anda</b><br>Seret pin ini untuk memindahkan lokasi.').openPopup();

                // Listen to click on map to move marker
                this.map.on('click', (e) => {
                    this.latitude = e.latlng.lat;
                    this.longitude = e.latlng.lng;
                    this.marker.setLatLng(e.latlng);
                });

                // Listen to drag ends
                this.marker.on('dragend', (e) => {
                    const position = this.marker.getLatLng();
                    this.latitude = position.lat;
                    this.longitude = position.lng;
                });
            }
        }
    }
</script>
@endsection
