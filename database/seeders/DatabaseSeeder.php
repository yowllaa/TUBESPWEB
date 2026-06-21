<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\KategoriDarurat;
use App\Models\KategoriPelaporan;
use App\Models\Instansi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Kategori Darurat
        $kategoriDarurat = [
            'Kebakaran',
            'Kecelakaan',
            'Darurat Medis',
            'Gangguan Keamanan',
            'Bencana Alam'
        ];

        foreach ($kategoriDarurat as $kategori) {
            KategoriDarurat::create([
                'nama_kategori' => $kategori
            ]);
        }

        // 2. Seed Kategori Pelaporan
        $kategoriPelaporan = [
            'Jalan Rusak',
            'Lampu Jalan Mati',
            'Sampah Menumpuk',
            'Saluran Tersumbat',
            'Pohon Tumbang',
            'Fasilitas Umum Rusak',
            'Lainnya'
        ];

        foreach ($kategoriPelaporan as $kategori) {
            KategoriPelaporan::create([
                'nama_kategori' => $kategori
            ]);
        }

        // 3. Seed Users
        // Admin
        User::create([
            'name' => 'Admin Darurat Lombok',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Kantor Gubernur NTB, Jl. Pejanggik No. 12, Mataram, Lombok',
        ]);

        // Masyarakat
        User::create([
            'name' => 'Masyarakat Lombok Sejahtera',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'masyarakat',
            'phone' => '089876543210',
            'address' => 'Jl. Senggigi No. 15, Batu Layar, Lombok Barat',
        ]);

        // Instansi Users & Instansi Details
        // Damkar
        $damkarUser = User::create([
            'name' => 'Petugas Damkar Mataram',
            'email' => 'damkar@example.com',
            'password' => Hash::make('password'),
            'role' => 'instansi',
            'phone' => '085337290385',
            'address' => 'Mako Damkar Kota Mataram, Lombok',
        ]);

        Instansi::create([
            'user_id' => $damkarUser->id,
            'nama_instansi' => 'Pemadam Kebakaran Kota Mataram',
            'kategori_instansi' => 'Kebakaran',
            'alamat' => 'Jl. Pejanggik No. 10, Mataram, Lombok',
            'nomor_telepon' => '085337290385',
            'email' => 'damkar.mataram@mataramkota.go.id',
            'latitude' => -8.58870000,
            'longitude' => 116.10220000,
            'wilayah' => 'Kota Mataram',
        ]);

        // RS / Medis
        $medisUser = User::create([
            'name' => 'Petugas RSUD Provinsi NTB',
            'email' => 'medis@example.com',
            'password' => Hash::make('password'),
            'role' => 'instansi',
            'phone' => '085337290385',
            'address' => 'RSUD Provinsi NTB, Mataram, Lombok',
        ]);

        Instansi::create([
            'user_id' => $medisUser->id,
            'nama_instansi' => 'RSUD Provinsi NTB (IGD & Ambulans)',
            'kategori_instansi' => 'Darurat Medis',
            'alamat' => 'Jl. Prabu Rangkasari, Dasan Cermen, Mataram, Lombok',
            'nomor_telepon' => '085337290385',
            'email' => 'rsud@ntbprov.go.id',
            'latitude' => -8.60810000,
            'longitude' => 116.13400000,
            'wilayah' => 'Kota Mataram',
        ]);

        // Polsek / Keamanan
        $polisiUser = User::create([
            'name' => 'Petugas Polresta Mataram',
            'email' => 'polisi@example.com',
            'password' => Hash::make('password'),
            'role' => 'instansi',
            'phone' => '085337290385',
            'address' => 'Polresta Mataram, Lombok',
        ]);

        Instansi::create([
            'user_id' => $polisiUser->id,
            'nama_instansi' => 'Polresta Mataram',
            'kategori_instansi' => 'Gangguan Keamanan',
            'alamat' => 'Jl. Pejanggik No. 49, Mataram, Lombok',
            'nomor_telepon' => '085337290385',
            'email' => 'polresta.mataram@polri.go.id',
            'latitude' => -8.58660000,
            'longitude' => 116.10650000,
            'wilayah' => 'Kota Mataram',
        ]);

        // BPBD / Bencana
        $bpbdUser = User::create([
            'name' => 'Petugas BPBD NTB',
            'email' => 'bpbd@example.com',
            'password' => Hash::make('password'),
            'role' => 'instansi',
            'phone' => '085337290385',
            'address' => 'Pusdalops BPBD Provinsi NTB, Mataram, Lombok',
        ]);

        Instansi::create([
            'user_id' => $bpbdUser->id,
            'nama_instansi' => 'BPBD Provinsi NTB',
            'kategori_instansi' => 'Bencana Alam',
            'alamat' => 'Jl. Langko No. 23, Mataram, Lombok',
            'nomor_telepon' => '085337290385',
            'email' => 'bpbd@ntbprov.go.id',
            'latitude' => -8.58020000,
            'longitude' => 116.08630000,
            'wilayah' => 'Provinsi NTB',
        ]);

        // Dinas Pekerjaan Umum (Fasilitas / Jalan / Lampu)
        $puUser = User::create([
            'name' => 'Petugas Dinas PUPR NTB',
            'email' => 'pu@example.com',
            'password' => Hash::make('password'),
            'role' => 'instansi',
            'phone' => '085337290385',
            'address' => 'Dinas PUPR Provinsi NTB, Mataram, Lombok',
        ]);

        Instansi::create([
            'user_id' => $puUser->id,
            'nama_instansi' => 'Dinas PUPR Provinsi NTB',
            'kategori_instansi' => 'Lainnya',
            'alamat' => 'Jl. Langko No. 63, Mataram, Lombok',
            'nomor_telepon' => '085337290385',
            'email' => 'pupr@ntbprov.go.id',
            'latitude' => -8.58150000,
            'longitude' => 116.09100000,
            'wilayah' => 'Provinsi NTB',
        ]);
    }
}
