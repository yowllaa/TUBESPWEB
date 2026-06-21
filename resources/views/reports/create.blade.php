@extends('layouts.dashboard')

@section('page-title', 'Buat Laporan Publik')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="reportForm()">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Formulir Pelaporan Masalah Publik</h3>
            <p class="text-xs text-slate-400">Pastikan data yang diisi valid dan lokasi ditentukan dengan tepat di peta.</p>
        </div>

        <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Grid Judul & Kategori -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Judul Laporan -->
                <div class="space-y-1.5">
                    <label for="judul" class="text-sm font-bold text-slate-700">Judul Kejadian / Laporan</label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required placeholder="Contoh: Lampu Jalan Mati di Jl. Anggrek"
                           class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('judul') border-red-500 @enderror">
                    @error('judul')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori Laporan -->
                <div class="space-y-1.5">
                    <label for="kategori_pelaporan_id" class="text-sm font-bold text-slate-700">Kategori Laporan</label>
                    <select id="kategori_pelaporan_id" name="kategori_pelaporan_id" required
                            class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('kategori_pelaporan_id') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('kategori_pelaporan_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_pelaporan_id')
                        <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi Kejadian -->
            <div class="space-y-1.5">
                <label for="deskripsi" class="text-sm font-bold text-slate-700">Deskripsi / Kronologi Detail</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required placeholder="Jelaskan secara rinci permasalahan yang terjadi..."
                          class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Foto Bukti -->
            <div class="space-y-1.5" x-data="{ photoName: null, photoPreview: null }">
                <label class="text-sm font-bold text-slate-700">Foto Bukti Laporan (Maks. 2MB)</label>
                
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-2xl hover:border-blue-500 transition">
                    <div class="space-y-2 text-center">
                        <!-- Preview Image if present -->
                        <div x-show="photoPreview" class="mb-4">
                            <img :src="photoPreview" class="mx-auto h-40 w-auto rounded-lg object-cover shadow-sm">
                        </div>

                        <!-- Standard Placeholder -->
                        <div x-show="!photoPreview" class="text-slate-400">
                            <svg class="mx-auto h-12 w-12 text-slate-300" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>

                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="foto" class="relative cursor-pointer bg-white rounded-md font-bold text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                <span>Unggah Foto</span>
                                <input id="foto" name="foto" type="file" required accept="image/*" class="sr-only"
                                       @change="
                                            const file = $event.target.files[0];
                                            if (file) {
                                                photoName = file.name;
                                                const reader = new FileReader();
                                                reader.onload = (e) => { photoPreview = e.target.result; };
                                                reader.readAsDataURL(file);
                                            }
                                       ">
                            </label>
                        </div>
                        <p class="text-xs text-slate-400">PNG, JPG, JPEG up to 2MB</p>
                        <p x-show="photoName" class="text-xs text-slate-500 font-semibold" x-text="photoName"></p>
                    </div>
                </div>
                @error('foto')
                    <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Detail Alamat Alamat Tekstual -->
            <div class="space-y-1.5">
                <label for="lokasi" class="text-sm font-bold text-slate-700">Detail Alamat Kejadian / Lokasi Tekstual</label>
                <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi') }}" required placeholder="Contoh: Jl. Senggigi No.15, Batu Layar, Lombok Barat, dekat hotel."
                       class="w-full rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 @error('lokasi') border-red-500 @enderror">
                @error('lokasi')
                    <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Map Picker (Latitude & Longitude) -->
            <div class="space-y-3">
                <label class="text-sm font-bold text-slate-700">Tentukan Lokasi Presisi di Peta (Klik pada peta untuk menaruh Pin)</label>
                
                <!-- Hidden inputs for coordinates -->
                <input type="hidden" id="latitude" name="latitude" x-model="latitude">
                <input type="hidden" id="longitude" name="longitude" x-model="longitude">

                <!-- Map Box -->
                <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-inner">
                    <div id="picker-map" class="w-full h-80 z-0"></div>
                </div>

                <!-- GPS Alert Message inside view -->
                <div class="flex items-center justify-between text-xs p-3 bg-slate-100 rounded-xl">
                    <span class="text-slate-500">Koordinat terpilih: <span class="font-bold text-slate-700" x-text="latitude.toFixed(6) + ', ' + longitude.toFixed(6)"></span></span>
                    <button type="button" @click="detectCurrentLocation()" 
                            class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition">
                        Gunakan GPS Saya
                    </button>
                </div>
                @error('latitude')
                    <p class="text-xs font-semibold text-red-600 mt-1">Titik peta wajib disematkan.</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
                <a href="{{ route('reports.index') }}" class="px-5 py-3 border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg shadow-blue-500/10 transition">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function reportForm() {
        return {
            latitude: {{ old('latitude') ?: -8.58870000 }},
            longitude: {{ old('longitude') ?: 116.10220000 }},
            map: null,
            marker: null,

            init() {
                // Initialize Map
                this.map = L.map('picker-map').setView([this.latitude, this.longitude], 14);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(this.map);

                // Add marker
                this.marker = L.marker([this.latitude, this.longitude], { draggable: true }).addTo(this.map);

                // Click on map to position pin
                this.map.on('click', (e) => {
                    this.latitude = e.latlng.lat;
                    this.longitude = e.latlng.lng;
                    this.marker.setLatLng(e.latlng);
                });

                // Listen to marker drag events
                this.marker.on('dragend', (e) => {
                    const position = this.marker.getLatLng();
                    this.latitude = position.lat;
                    this.longitude = position.lng;
                });

                // Detect current location if first time
                @if(!old('latitude'))
                    this.detectCurrentLocation();
                @endif
            },

            detectCurrentLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            this.marker.setLatLng([this.latitude, this.longitude]);
                            this.map.setView([this.latitude, this.longitude], 15);
                        },
                        (error) => {
                            console.warn('Geolocation failed: ' + error.message);
                        }
                    );
                }
            }
        }
    }
</script>
@endsection
