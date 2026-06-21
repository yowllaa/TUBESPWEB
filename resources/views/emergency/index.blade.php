@extends('layouts.dashboard')

@section('page-title', 'Panggilan Darurat Kedaruratan')

@section('content')
<div class="space-y-6" x-data="emergencySearch()">
    <!-- Top Filter Header -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Cari Bantuan Pos Darurat Terdekat</h2>
            <p class="text-xs text-slate-400">Pilih kategori darurat untuk menemukan nomor kontak dan lokasi instansi terdekat.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <label for="category-select" class="text-sm font-bold text-slate-600">Pilih Kategori:</label>
            <select id="category-select" x-model="selectedCategory" @change="fetchAgencies()" 
                    class="rounded-xl border-slate-200 text-sm font-medium focus:border-red-500 focus:ring-red-500">
                @foreach($kategoriDarurat as $cat)
                    <option value="{{ $cat->nama_kategori }}">{{ $cat->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Alert / Status Banner -->
    <div class="p-4 rounded-xl text-sm font-medium flex items-center justify-between transition duration-200"
         :class="statusClass" x-show="statusMessage">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 animate-spin" x-show="loading" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span x-text="statusMessage">Mendeteksi lokasi GPS Anda...</span>
        </div>
        <button x-show="!loading && !locationGranted" @click="requestLocation()" 
                class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition">
            Aktifkan GPS
        </button>
    </div>

    <!-- Main Content Split (Map and List) -->
    <div class="grid lg:grid-cols-12 gap-6 h-[calc(100vh-270px)] min-h-[500px]">
        <!-- Left Side: Nearest Agencies List (5 cols) -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                <h3 class="font-bold text-slate-800 flex items-center">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-red-600 mr-2"></span>
                    Pos Terdekat Ditemukan (<span x-text="agencies.length">0</span>)
                </h3>
            </div>

            <!-- Scrollable list of results -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-100">
                <!-- If loading -->
                <div x-show="loading" class="p-8 text-center space-y-3">
                    <div class="inline-flex items-center justify-center p-3 bg-red-50 text-[#DC2626] rounded-full animate-bounce">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Sedang memproses rute dan jarak...</p>
                </div>

                <!-- If empty -->
                <div x-show="!loading && agencies.length === 0" class="p-8 text-center text-slate-400">
                    Tidak ada instansi terdekat yang cocok dengan kategori ini dalam data kami.
                </div>

                <!-- Agency Loop -->
                <template x-for="(agency, index) in agencies" :key="agency.id">
                    <div class="p-6 space-y-4 hover:bg-slate-50 transition duration-150" 
                         :class="index === 0 ? 'bg-red-50/20 border-l-4 border-red-600' : ''"
                         @click="focusAgency(agency)">
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-start">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase bg-red-100 text-red-800"
                                      x-text="agency.kategori_instansi">
                                </span>
                                <span class="text-xs font-bold text-slate-700" 
                                      :class="index === 0 ? 'text-red-600 font-extrabold text-sm' : 'text-slate-500'">
                                    ⚡ Jarak: <span x-text="agency.distance"></span> km
                                </span>
                            </div>
                            <h4 class="font-bold text-slate-800 text-base" x-text="agency.nama_instansi"></h4>
                            <p class="text-xs text-slate-400" x-text="agency.alamat"></p>
                        </div>

                        <div class="flex gap-2">
                            <a :href="'tel:' + agency.nomor_telepon" 
                               class="flex-1 py-3 px-4 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-sm text-center flex items-center justify-center space-x-2 transition duration-200">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>HUBUNGI SEKARANG</span>
                            </a>
                            <button type="button" @click.stop="focusAgency(agency)"
                                    class="py-3 px-3 border border-slate-200 hover:bg-slate-100 rounded-xl text-slate-500 transition duration-200">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Right Side: Interactive Leaflet Map (7 cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden relative h-full flex flex-col">
            <!-- Map Container -->
            <div id="map" class="flex-1 w-full h-full z-0"></div>

            <!-- Manual Pin Helper (Floating Box) -->
            <div class="absolute bottom-4 left-4 right-4 z-10 bg-white/90 backdrop-blur-md p-3.5 rounded-xl border border-slate-200 shadow-lg text-xs space-y-1.5 pointer-events-auto max-w-sm">
                <div class="flex items-center space-x-1.5 text-slate-800 font-bold">
                    <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Penentuan Lokasi Manual</span>
                </div>
                <p class="text-slate-500 leading-normal">
                    Jika GPS tidak akurat, Anda dapat **menggeser pin biru** di peta untuk menyesuaikan posisi Anda secara manual. Sistem akan menghitung ulang jarak secara otomatis.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function emergencySearch() {
        return {
            selectedCategory: new URLSearchParams(window.location.search).get('kategori') || 'Kebakaran',
            latitude: -8.58870000, // Mataram default simulated
            longitude: 116.10220000,
            agencies: [],
            loading: false,
            statusMessage: 'Meminta izin lokasi GPS...',
            statusClass: 'bg-slate-100 text-slate-700',
            locationGranted: false,
            map: null,
            userMarker: null,
            markersGroup: null,
            routingLine: null,

            init() {
                // Initialize map
                this.map = L.map('map').setView([this.latitude, this.longitude], 14);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(this.map);

                this.markersGroup = L.layerGroup().addTo(this.map);

                // Add draggable user marker
                const blueIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                this.userMarker = L.marker([this.latitude, this.longitude], {
                    draggable: true,
                    icon: blueIcon
                }).addTo(this.map);

                this.userMarker.bindPopup('<b>Lokasi Anda</b><br>Seret pin ini untuk koreksi manual.').openPopup();

                // Listen to drag events to recalculate
                this.userMarker.on('dragend', (e) => {
                    const position = this.userMarker.getLatLng();
                    this.latitude = position.lat;
                    this.longitude = position.lng;
                    this.statusMessage = 'Koordinat disesuaikan secara manual. Menghitung kembali...';
                    this.statusClass = 'bg-blue-50 text-blue-800';
                    this.fetchAgencies();
                });

                // Request location
                this.requestLocation();
            },

            requestLocation() {
                if (navigator.geolocation) {
                    this.loading = true;
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            this.locationGranted = true;
                            this.loading = false;
                            this.statusMessage = 'GPS aktif. Koordinat diperoleh secara presisi.';
                            this.statusClass = 'bg-green-50 text-green-800 border-l-4 border-green-500';
                            
                            // Move user marker
                            this.userMarker.setLatLng([this.latitude, this.longitude]);
                            this.map.setView([this.latitude, this.longitude], 14);
                            
                            this.fetchAgencies();
                        },
                        (error) => {
                            this.loading = false;
                            this.locationGranted = false;
                            this.statusMessage = 'GPS tidak dapat diakses (' + error.message + '). Silakan gunakan penentuan lokasi manual dengan menggeser pin biru.';
                            this.statusClass = 'bg-amber-50 text-amber-800 border-l-4 border-amber-500';
                            this.fetchAgencies();
                        },
                        { enableHighAccuracy: true, timeout: 5000 }
                    );
                } else {
                    this.statusMessage = 'Browser Anda tidak mendukung deteksi lokasi. Gunakan penentuan lokasi manual.';
                    this.statusClass = 'bg-amber-50 text-amber-800 border-l-4 border-amber-500';
                    this.fetchAgencies();
                }
            },

            fetchAgencies() {
                this.loading = true;
                
                fetch('{{ route("emergency.search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        latitude: this.latitude,
                        longitude: this.longitude,
                        kategori: this.selectedCategory
                    })
                })
                .then(response => response.json())
                .then(data => {
                    this.agencies = data;
                    this.loading = false;
                    this.renderMarkers();
                })
                .catch(error => {
                    this.loading = false;
                    console.error('Error fetching agencies:', error);
                });
            },

            renderMarkers() {
                // Clear previous markers & lines
                this.markersGroup.clearLayers();
                if (this.routingLine) {
                    this.map.removeLayer(this.routingLine);
                }

                if (this.agencies.length === 0) return;

                const redIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                const bounds = [this.userMarker.getLatLng()];

                this.agencies.forEach((agency, index) => {
                    const latlng = [agency.latitude, agency.longitude];
                    bounds.push(latlng);

                    const marker = L.marker(latlng, { icon: redIcon })
                        .bindPopup(`<b>${agency.nama_instansi}</b><br>${agency.kategori_instansi}<br>Jarak: ${agency.distance} km<br><a href="tel:${agency.nomor_telepon}" class="block mt-2 px-3 py-1 bg-red-600 text-white text-[10px] font-bold text-center rounded">Hubungi</a>`)
                        .addTo(this.markersGroup);

                    // Connect line to nearest (index 0)
                    if (index === 0) {
                        this.routingLine = L.polyline([this.userMarker.getLatLng(), latlng], {
                            color: 'red',
                            dashArray: '5, 10',
                            weight: 3
                        }).addTo(this.map);
                    }
                });

                // Auto zoom & fit markers
                this.map.fitBounds(bounds, { padding: [50, 50] });
            },

            focusAgency(agency) {
                const latlng = [agency.latitude, agency.longitude];
                this.map.setView(latlng, 16);
                
                // Find and open popup for this marker
                this.markersGroup.eachLayer((layer) => {
                    if (layer.getLatLng().lat === agency.latitude && layer.getLatLng().lng === agency.longitude) {
                        layer.openPopup();
                    }
                });
            }
        }
    }
</script>
@endsection
