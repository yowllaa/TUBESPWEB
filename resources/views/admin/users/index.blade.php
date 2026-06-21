@extends('layouts.dashboard')

@section('page-title', 'Kelola Pengguna')

@section('content')
<div class="space-y-6" x-data="usersManagement()">
    <!-- Header with Create Button -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Manajemen Pengguna</h2>
            <p class="text-xs text-slate-400">Kelola semua pengguna sistem (Admin, Masyarakat, dan Instansi).</p>
        </div>
        <button type="button" @click="openCreate()" 
                class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm hover:shadow-md transition">
            + Tambah Pengguna
        </button>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex justify-between items-center">
            <span class="text-xs font-bold text-slate-400 uppercase">Daftar Pengguna Sistem</span>
            <!-- Search bar -->
            <input type="text" x-model="search" placeholder="Cari nama atau email..." 
                   class="px-3 py-1.5 rounded-lg border-slate-200 text-xs focus:border-blue-500 focus:ring-blue-500 w-64">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold">
                    <tr>
                        <th class="px-6 py-3">Nama</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Peran (Role)</th>
                        <th class="px-6 py-3">Telepon</th>
                        <th class="px-6 py-3">Alamat</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    <template x-for="user in filteredUsers()" :key="user.id">
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-bold text-slate-800" x-text="user.name"></td>
                            <td class="px-6 py-4" x-text="user.email"></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase"
                                      :class="
                                        user.role === 'admin' ? 'bg-red-100 text-red-800' :
                                        user.role === 'instansi' ? 'bg-purple-100 text-purple-800' :
                                        'bg-blue-100 text-blue-800'
                                      "
                                      x-text="user.role">
                                </span>
                            </td>
                            <td class="px-6 py-4" x-text="user.phone || '-'"></td>
                            <td class="px-6 py-4 max-w-xs truncate" x-text="user.address || '-'"></td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <button type="button" @click="openEdit(user)" class="text-blue-600 hover:text-blue-800 font-semibold">Ubah</button>
                                <form :action="'{{ url('/admin/users') }}/' + user.id" method="POST" class="inline-block" @submit.prevent="confirmDelete($el)">
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

    <!-- Create Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 overflow-y-auto px-4" x-show="showCreateModal" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl w-full max-w-lg overflow-hidden" @click.away="showCreateModal = false">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Tambah Pengguna Baru</h3>
                <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <!-- Nama -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full rounded-xl border-slate-200 text-sm">
                </div>

                <!-- Email -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Alamat Email</label>
                    <input type="email" name="email" required class="w-full rounded-xl border-slate-200 text-sm">
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Password</label>
                    <input type="password" name="password" required class="w-full rounded-xl border-slate-200 text-sm">
                </div>

                <!-- Role / Peran -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Peran (Role)</label>
                    <select name="role" required class="w-full rounded-xl border-slate-200 text-sm">
                        <option value="masyarakat">Masyarakat</option>
                        <option value="instansi">Instansi</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <!-- Telepon & Alamat -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Nomor Telepon</label>
                        <input type="text" name="phone" class="w-full rounded-xl border-slate-200 text-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Alamat Rumah</label>
                        <input type="text" name="address" class="w-full rounded-xl border-slate-200 text-sm">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end space-x-2">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 overflow-y-auto px-4" x-show="showEditModal" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl w-full max-w-lg overflow-hidden" @click.away="showEditModal = false">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800">Edit Pengguna</h3>
                <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">✕</button>
            </div>

            <form :action="'{{ url('/admin/users') }}/' + editingUser.id" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PATCH')
                <!-- Nama -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Nama Lengkap</label>
                    <input type="text" name="name" required x-model="editingUser.name" class="w-full rounded-xl border-slate-200 text-sm">
                </div>

                <!-- Email -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Alamat Email</label>
                    <input type="email" name="email" required x-model="editingUser.email" class="w-full rounded-xl border-slate-200 text-sm">
                </div>

                <!-- Password (Optional) -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Password Baru <span class="text-[10px] text-slate-400 font-normal">(Biarkan kosong jika tidak diubah)</span></label>
                    <input type="password" name="password" class="w-full rounded-xl border-slate-200 text-sm">
                </div>

                <!-- Role / Peran -->
                <div class="space-y-1">
                    <label class="text-xs font-bold text-slate-700">Peran (Role)</label>
                    <select name="role" required x-model="editingUser.role" class="w-full rounded-xl border-slate-200 text-sm">
                        <option value="masyarakat">Masyarakat</option>
                        <option value="instansi">Instansi</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>

                <!-- Telepon & Alamat -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Nomor Telepon</label>
                        <input type="text" name="phone" x-model="editingUser.phone" class="w-full rounded-xl border-slate-200 text-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-700">Alamat Rumah</label>
                        <input type="text" name="address" x-model="editingUser.address" class="w-full rounded-xl border-slate-200 text-sm">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 flex justify-end space-x-2">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 border border-slate-200 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function usersManagement() {
        return {
            users: @json($users),
            search: '',
            showCreateModal: false,
            showEditModal: false,
            editingUser: { id: null, name: '', email: '', role: 'masyarakat', phone: '', address: '' },

            filteredUsers() {
                if (this.search.trim() === '') {
                    return this.users;
                }
                const term = this.search.toLowerCase();
                return this.users.filter(u => 
                    u.name.toLowerCase().includes(term) || 
                    u.email.toLowerCase().includes(term)
                );
            },

            openCreate() {
                this.showCreateModal = true;
            },

            openEdit(user) {
                this.editingUser = Object.assign({}, user);
                this.showEditModal = true;
            },

            confirmDelete(form) {
                if (confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini mendukung soft delete.')) {
                    form.submit();
                }
            }
        }
    }
</script>
@endsection
