@extends('layouts.dashboard')

@section('page-title', 'Kelola Kategori')

@section('content')
<div class="grid md:grid-cols-2 gap-8">
    <!-- Left Column: Kategori Darurat -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Kategori Layanan Darurat</h3>
            <p class="text-xs text-slate-400">Daftar kategori tanggap darurat yang aktif di halaman beranda & dashboard user.</p>
        </div>

        <!-- Add Category Form -->
        <form action="{{ route('admin.categories.emergency.store') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="nama_kategori" required placeholder="Contoh: Kedaruratan Nuklir"
                   class="flex-1 rounded-xl border-slate-200 text-sm focus:border-red-500 focus:ring-red-500">
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                Tambah
            </button>
        </form>
        @error('nama_kategori')
            <p class="text-xs font-semibold text-red-600 mt-1">{{ $message }}</p>
        @enderror

        <!-- Categories List Table -->
        <div class="overflow-hidden border border-slate-100 rounded-xl">
            <table class="min-w-full divide-y divide-slate-150 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold">
                    <tr>
                        <th class="px-4 py-3">Nama Kategori</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($emergencyCategories as $cat)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $cat->nama_kategori }}</td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('admin.categories.emergency.delete', $cat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori darurat ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-4 text-center text-slate-400">Belum ada kategori darurat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Column: Kategori Pelaporan -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div>
            <h3 class="font-bold text-slate-800 text-lg">Kategori Pelaporan Publik</h3>
            <p class="text-xs text-slate-400">Daftar klasifikasi keluhan non-darurat untuk pelaporan masyarakat umum.</p>
        </div>

        <!-- Add Category Form -->
        <form action="{{ route('admin.categories.reporting.store') }}" method="POST" class="flex gap-2">
            @csrf
            <input type="text" name="nama_kategori" required placeholder="Contoh: Saluran Gas Bocor"
                   class="flex-1 rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                Tambah
            </button>
        </form>

        <!-- Categories List Table -->
        <div class="overflow-hidden border border-slate-100 rounded-xl">
            <table class="min-w-full divide-y divide-slate-150 text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-bold">
                    <tr>
                        <th class="px-4 py-3">Nama Kategori</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($reportingCategories as $cat)
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3 font-semibold text-slate-800">{{ $cat->nama_kategori }}</td>
                            <td class="px-4 py-3 text-right">
                                <form action="{{ route('admin.categories.reporting.delete', $cat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori pelaporan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="p-4 text-center text-slate-400">Belum ada kategori pelaporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
