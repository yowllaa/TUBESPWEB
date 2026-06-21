@extends('layouts.dashboard')

@section('page-title', 'Daftar Semua Laporan')

@section('content')
<div class="space-y-6" x-data="adminReports()">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Monitoring Laporan Pengaduan</h2>
            <p class="text-xs text-slate-400">Pantau dan kelola semua aduan masalah publik yang diajukan oleh masyarakat.</p>
        </div>
    </div>

    <!-- Reports Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center bg-slate-50 gap-4">
            <span class="text-xs font-bold text-slate-400 uppercase">Daftar Aduan Masuk</span>
            <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                <!-- Status Filter -->
                <select x-model="statusFilter" class="rounded-lg border-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Semua Status --</option>
                    <option value="Menunggu">Menunggu</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Selesai">Selesai</option>
                </select>
                <!-- Search bar -->
                <input type="text" x-model="search" placeholder="Cari aduan atau pengirim..." 
                       class="px-3 py-1.5 rounded-lg border-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500 w-64">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-100 text-slate-500 uppercase font-bold">
                    <tr>
                        <th class="px-6 py-3">Bukti</th>
                        <th class="px-6 py-3">Judul Aduan</th>
                        <th class="px-6 py-3">Kategori</th>
                        <th class="px-6 py-3">Pelapor</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Petugas Penjawab</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <template x-for="rep in filteredReports()" :key="rep.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <img :src="'{{ asset('storage') }}/' + rep.foto" alt="Bukti" class="w-10 h-10 object-cover rounded-lg border">
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800" x-text="rep.judul"></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-50 text-blue-800"
                                      x-text="rep.kategori_pelaporan.nama_kategori">
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold" x-text="rep.user.name"></div>
                                <div class="text-[10px] text-slate-400" x-text="rep.user.email"></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                                      :class="
                                        rep.status === 'Menunggu' ? 'bg-slate-100 text-slate-800' :
                                        rep.status === 'Diproses' ? 'bg-yellow-100 text-yellow-800' :
                                        'bg-green-100 text-green-800'
                                      ">
                                    <span class="w-1 h-1 rounded-full mr-1"
                                          :class="
                                            rep.status === 'Menunggu' ? 'bg-slate-400' :
                                            rep.status === 'Diproses' ? 'bg-yellow-500' :
                                            'bg-green-500'
                                          "></span>
                                    <span x-text="rep.status"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-600" 
                                      x-text="rep.tindak_lanjut && rep.tindak_lanjut.length > 0 ? rep.tindak_lanjut[0].instansi.nama_instansi : 'Belum Ditugaskan'">
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <form :action="'{{ url('/admin/reports') }}/' + rep.id" method="POST" class="inline-block" @submit.prevent="confirmDelete($el)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function adminReports() {
        return {
            reports: @json($reports),
            search: '',
            statusFilter: '',

            filteredReports() {
                let list = this.reports;

                if (this.statusFilter !== '') {
                    list = list.filter(r => r.status === this.statusFilter);
                }

                if (this.search.trim() !== '') {
                    const term = this.search.toLowerCase();
                    list = list.filter(r => 
                        r.judul.toLowerCase().includes(term) || 
                        r.user.name.toLowerCase().includes(term) ||
                        r.kategori_pelaporan.nama_kategori.toLowerCase().includes(term)
                    );
                }

                return list;
            },

            confirmDelete(form) {
                if (confirm('Apakah Anda yakin ingin menghapus laporan ini? (Mendukung soft delete)')) {
                    form.submit();
                }
            }
        }
    }
</script>
@endsection
