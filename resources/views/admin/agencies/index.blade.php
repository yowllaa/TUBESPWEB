@extends('layouts.dashboard')

@section('page-title', 'Kelola Instansi')

@section('content')
<div class="space-y-6" x-data="agencyManagement()">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Manajemen Instansi</h2>
            <p class="text-xs text-slate-400">Kelola unit kerja instansi (Pemadam, Rumah Sakit, Polsek, BPBD) dan tautkan akun user login.</p>
        </div>
        <button type="button" @click="openCreate()" 
                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm hover:shadow-md transition">
            + Tambah Instansi
        </button>
    </div>

    <!-- Agency Grid List -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
            <span class="text-xs font-bold text-slate-400 uppercase">Daftar Instansi Terdaftar</span>
            <input type="text" x-model="search" placeholder="Cari instansi atau wilayah..." 
                   class="px-3 py-1.5 rounded-lg border-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500 w-64">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-100 text-slate-500 uppercase font-bold">
                    <tr>
                        <th class="px-6 py-3">Nama Instansi</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Wilayah</th>
                        <th class="px-6 py-3">Telepon</th>
                        <th class="px-6 py-3">User Login</th>
                        <th class="px-6 py-3">Koordinat</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <template x-for="agency in filteredAgencies()" :key="agency.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-800" x-text="agency.nama_instansi"></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase bg-red-100 text-red-800"
                                      x-text="agency.kategori_instansi">
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600" x-text="agency.wilayah"></td>
                            <td class="px-6 py-4 text-slate-600" x-text="agency.nomor_telepon"></td>
                            <td class="px-6 py-4">
                                <span class="text-slate-500" x-text="agency.user ? agency.user.name : 'Belum Ditautkan'"></span>
                            </td>
                            <td class="px-6 py-4 font-mono text-[10px] text-slate-400" x-text="agency.latitude + ', ' + agency.longitude"></td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button type="button" @click="openEdit(agency)" class="text-blue-600 hover:text-blue-800 font-semibold">Ubah</button>
                                <form :action="'{{ url('/admin/agencies') }}/' + agency.id" method="POST" class="inline-block" @submit.prevent="confirmDelete($el)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold ml-2">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create / Edit Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 overflow-y-auto px-4" 
         x-show="showModal" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl w-full max-w-2xl overflow-hidden" @click.away="showModal = false">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800" x-text="editMode ? 'Ubah Informasi Instansi' : 'Tambah Instansi Baru'"></h3>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form :action="editMode ? '{{ url('/admin/agencies') }}/' + form.id : '{{ route('admin.agencies.store') }}'" 
                  method="POST" class="p-6 space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PATCH">
                </template>

                <div class="grid md:grid-cols-2 gap-4">
                    <!-- Nama Instansi -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Nama Instansi</label>
                        <input type="text" name="nama_instansi" required x-model="form.nama_instansi" class="w-full rounded-xl border-slate-200 text-sm">
                    </div>

                    <!-- Kategori Instansi -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Kategori Penanganan</label>
                        <select name="kategori_instansi" required x-model="form.kategori_instansi" class="w-full rounded-xl border-slate-200 text-sm">
                            <option value="Kebakaran">Kebakaran</option>
                            <option value="Darurat Medis">Darurat Medis</option>
                            <option value="Gangguan Keamanan">Gangguan Keamanan</option>
                            <option value="Bencana Alam">Bencana Alam</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-4">
                    <!-- Telepon -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Nomor Telepon Darurat</label>
                        <input type="text" name="nomor_telepon" required x-model="form.nomor_telepon" class="w-full rounded-xl border-slate-200 text-sm">
                    </div>
                    <!-- Email -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Email Resmi</label>
                        <input type="email" name="email" x-model="form.email" class="w-full rounded-xl border-slate-200 text-sm">
                    </div>
                    <!-- Wilayah -->
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Wilayah Kerja</label>
                        <input type="text" name="wilayah" required x-model="form.wilayah" class="w-full rounded-xl border-slate-200 text-sm">
                    </div>
                </div>

                <!-- Tautkan User Login -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Tautkan Akun Petugas (User Role Instansi)</label>
                    <select name="user_id" x-model="form.user_id" class="w-full rounded-xl border-slate-200 text-sm">
                        <option value="">-- Tanpa Tautan Akun --</option>
                        <template x-for="user in availableUsers()" :key="user.id">
                            <option :value="user.id" x-text="user.name + ' (' + user.email + ')'"></option>
                        </template>
                    </select>
                </div>

                <!-- Alamat Fisik -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Alamat Kantor Pos</label>
                    <textarea name="alamat" rows="2" required x-model="form.alamat" class="w-full rounded-xl border-slate-200 text-sm"></textarea>
                </div>

                <!-- Peta Mini Picker -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-700">Tentukan Lokasi Peta (Klik untuk memindahkan pin marker)</label>
                    
                    <input type="hidden" name="latitude" x-model="form.latitude">
                    <input type="hidden" name="longitude" x-model="form.longitude">

                    <div class="border border-slate-200 rounded-xl overflow-hidden shadow-inner">
                        <div id="admin-agency-map" class="w-full h-48 z-0"></div>
                    </div>
                    
                    <span class="text-[10px] text-slate-400 font-mono">
                        Koordinat: <span x-text="form.latitude + ', ' + form.longitude"></span>
                    </span>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end space-x-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function agencyManagement() {
        return {
            agencies: @json($agencies),
            allInstansiUsers: @json($allInstansiUsers),
            search: '',
            showModal: false,
            editMode: false,
            map: null,
            marker: null,
            form: { id: null, user_id: '', nama_instansi: '', kategori_instansi: 'Kebakaran', alamat: '', nomor_telepon: '', email: '', latitude: -8.5887, longitude: 116.1022, wilayah: '' },

            filteredAgencies() {
                if (this.search.trim() === '') return this.agencies;
                const term = this.search.toLowerCase();
                return this.agencies.filter(a => 
                    a.nama_instansi.toLowerCase().includes(term) || 
                    a.wilayah.toLowerCase().includes(term) ||
                    a.kategori_instansi.toLowerCase().includes(term)
                );
            },

            availableUsers() {
                // Return users with role instansi who either are not linked to any agency OR is linked to the current editing agency
                return this.allInstansiUsers.filter(u => {
                    const isLinkedElsewhere = this.agencies.some(a => a.user_id === u.id && a.id !== this.form.id);
                    return !isLinkedElsewhere;
                });
            },

            openCreate() {
                this.editMode = false;
                this.form = { id: null, user_id: '', nama_instansi: '', kategori_instansi: 'Kebakaran', alamat: '', nomor_telepon: '', email: '', latitude: -8.5887, longitude: 116.1022, wilayah: '' };
                this.showModal = true;
                this.initMap();
            },

            openEdit(agency) {
                this.editMode = true;
                this.form = Object.assign({}, agency);
                this.showModal = true;
                this.initMap();
            },

            initMap() {
                // Wait for Alpine to render modal DOM, then setup Leaflet
                setTimeout(() => {
                    if (this.map) {
                        this.map.remove();
                    }

                    this.map = L.map('admin-agency-map').setView([this.form.latitude, this.form.longitude], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                    }).addTo(this.map);

                    this.marker = L.marker([this.form.latitude, this.form.longitude], { draggable: true }).addTo(this.map);

                    this.map.on('click', (e) => {
                        this.form.latitude = e.latlng.lat;
                        this.form.longitude = e.latlng.lng;
                        this.marker.setLatLng(e.latlng);
                    });

                    this.marker.on('dragend', (e) => {
                        const position = this.marker.getLatLng();
                        this.form.latitude = position.lat;
                        this.form.longitude = position.lng;
                    });

                    // Invalidate size to load map tiles properly inside modal
                    this.map.invalidateSize();
                }, 100);
            },

            confirmDelete(form) {
                if (confirm('Apakah Anda yakin ingin menghapus instansi ini? (Mendukung soft delete)')) {
                    form.submit();
                }
            }
        }
    }
</script>
@endsection
