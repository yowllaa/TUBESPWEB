<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'SIP-DARURAT') }} - Layanan Darurat & Pelaporan Masyarakat</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#1F2937] antialiased">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 bg-white/85 backdrop-blur-md border-b border-slate-200" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Brand Logo -->
                <div class="flex items-center">
                    <a href="#" class="flex items-center space-x-2">
                        <div class="p-2 bg-[#DC2626] rounded-xl text-white shadow-md shadow-red-500/20">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-xl font-extrabold tracking-wider text-slate-900">SIP<span class="text-[#DC2626]">-DARURAT</span></span>
                    </a>
                </div>

                <!-- Desktop Nav Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#cara-kerja" class="text-sm font-semibold text-slate-600 hover:text-[#DC2626] transition duration-255">Cara Kerja</a>
                    <a href="#layanan-darurat" class="text-sm font-semibold text-slate-600 hover:text-[#DC2626] transition duration-255">Layanan Darurat</a>
                    <a href="#kategori-laporan" class="text-sm font-semibold text-slate-600 hover:text-[#DC2626] transition duration-255">Kategori Pelaporan</a>
                    <a href="#instansi" class="text-sm font-semibold text-slate-600 hover:text-[#DC2626] transition duration-255">Instansi Terdaftar</a>
                </div>

                <!-- Action Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-[#2563EB] text-white hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/10 transition duration-255">Ke Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-[#DC2626] transition duration-255">Masuk</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl text-sm font-bold bg-[#DC2626] text-white hover:bg-red-700 hover:shadow-lg hover:shadow-red-500/10 transition duration-255">Daftar</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-xl text-slate-500 hover:text-slate-600 hover:bg-slate-100 focus:outline-none">
                        <svg class="h-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path :class="{'hidden': open, 'inline-flex': !open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden bg-white border-b border-slate-200" x-show="open" style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#cara-kerja" class="block px-3 py-2 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#DC2626]">Cara Kerja</a>
                <a href="#layanan-darurat" class="block px-3 py-2 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#DC2626]">Layanan Darurat</a>
                <a href="#kategori-laporan" class="block px-3 py-2 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#DC2626]">Kategori Pelaporan</a>
                <a href="#instansi" class="block px-3 py-2 rounded-xl text-base font-semibold text-slate-700 hover:bg-slate-50 hover:text-[#DC2626]">Instansi Terdaftar</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-xl text-base font-bold bg-[#2563EB] text-white mt-4 text-center">Dashboard</a>
                @else
                    <div class="grid grid-cols-2 gap-2 mt-4 px-3">
                        <a href="{{ route('login') }}" class="block w-full py-2.5 text-center text-sm font-bold border border-slate-200 rounded-xl text-slate-700 hover:bg-slate-50">Masuk</a>
                        <a href="{{ route('register') }}" class="block w-full py-2.5 text-center text-sm font-bold bg-[#DC2626] text-white rounded-xl hover:bg-red-700">Daftar</a>
                    </div>
                @endif
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-slate-800 to-slate-950 text-white py-20 sm:py-32">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-red-600/10 via-transparent to-transparent"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-12 gap-12 items-center">
                <!-- Left Content -->
                <div class="md:col-span-7 space-y-6 text-center md:text-left">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider bg-red-500/10 text-red-400 border border-red-500/20 uppercase">
                        🔴 RESPONS CEPAT & PRESISI LOKASI
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                        Layanan Darurat & <br class="hidden sm:inline">
                        <span class="bg-gradient-to-r from-red-500 to-[#2563EB] bg-clip-text text-transparent">Pelaporan Masyarakat</span> <br class="hidden sm:inline">
                        Berbasis Lokasi
                    </h1>
                    <p class="text-base sm:text-lg text-slate-300 max-w-xl mx-auto md:mx-0">
                        Temukan pos instansi terdekat berdasarkan GPS lokasi Anda dan buat pelaporan masalah fasilitas publik dalam hitungan detik.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold bg-[#DC2626] hover:bg-red-700 shadow-lg shadow-red-500/20 transition duration-255 text-center">
                                Akses Layanan Sekarang
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold bg-[#DC2626] hover:bg-red-700 shadow-lg shadow-red-500/20 transition duration-255 text-center">
                                Daftar Akun
                            </a>
                            <a href="#cara-kerja" class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold bg-white/10 hover:bg-white/20 border border-white/10 transition duration-255 text-center">
                                Pelajari Cara Kerja
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Right Stats Cards -->
                <div class="md:col-span-5 grid grid-cols-2 gap-4">
                    <div class="p-6 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 hover:border-red-500/30 transition duration-300">
                        <div class="text-3xl font-extrabold text-red-500">{{ $totalLaporan }}</div>
                        <div class="text-xs text-slate-400 font-semibold tracking-wider uppercase mt-1">Total Laporan</div>
                    </div>
                    <div class="p-6 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 hover:border-blue-500/30 transition duration-300">
                        <div class="text-3xl font-extrabold text-[#2563EB]">{{ $totalInstansi }}</div>
                        <div class="text-xs text-slate-400 font-semibold tracking-wider uppercase mt-1">Instansi Terdaftar</div>
                    </div>
                    <div class="p-6 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 hover:border-green-500/30 transition duration-300">
                        <div class="text-3xl font-extrabold text-green-500">{{ $laporanSelesai }}</div>
                        <div class="text-xs text-slate-400 font-semibold tracking-wider uppercase mt-1">Laporan Selesai</div>
                    </div>
                    <div class="p-6 bg-white/5 backdrop-blur-md rounded-2xl border border-white/10 hover:border-yellow-500/30 transition duration-300">
                        <div class="text-3xl font-extrabold text-yellow-500">{{ $totalPengguna }}</div>
                        <div class="text-xs text-slate-400 font-semibold tracking-wider uppercase mt-1">Masyarakat Aktif</div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Section 2: Cara Kerja -->
    <section id="cara-kerja" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="text-sm font-bold text-[#DC2626] uppercase tracking-wider">Langkah Mudah</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Bagaimana Sistem Bekerja?</h2>
                <p class="text-slate-500">
                    Sistem dirancang untuk memberikan respon tercepat dalam kondisi darurat maupun pelaporan publik.
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <!-- Step 1 -->
                <div class="relative group text-center space-y-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-red-50 text-[#DC2626] font-bold text-xl group-hover:scale-105 transition duration-300 shadow-sm">
                        1
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Pilih Jenis Kejadian</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Pilih kategori darurat (Kebakaran, Keamanan, Medis) atau pilih buat laporan publik non-darurat.
                    </p>
                </div>

                <!-- Step 2 -->
                <div class="relative group text-center space-y-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-50 text-[#2563EB] font-bold text-xl group-hover:scale-105 transition duration-300 shadow-sm">
                        2
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Aktifkan Lokasi</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Izinkan GPS browser Anda agar sistem dapat mendeteksi titik koordinat Anda secara presisi.
                    </p>
                </div>

                <!-- Step 3 -->
                <div class="relative group text-center space-y-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-green-50 text-green-600 font-bold text-xl group-hover:scale-105 transition duration-300 shadow-sm">
                        3
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Temukan Pos Terdekat</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Sistem secara instan menghitung jarak Haversine dan mengurutkan instansi terdekat dalam radius Anda.
                    </p>
                </div>

                <!-- Step 4 -->
                <div class="relative group text-center space-y-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-yellow-50 text-yellow-600 font-bold text-xl group-hover:scale-105 transition duration-300 shadow-sm">
                        4
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Hubungi atau Laporkan</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">
                        Tekan panggilan telepon darurat instansi, atau buat laporan dengan menyertakan bukti foto.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Layanan Darurat -->
    <section id="layanan-darurat" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="text-sm font-bold text-[#DC2626] uppercase tracking-wider">Respon Cepat</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Kategori Kondisi Darurat</h2>
                <p class="text-slate-500">
                    Gunakan fitur darurat saat Anda membutuhkan respon instan dari instansi terdekat.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Card Kebakaran -->
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-md hover:border-red-500/30 transition duration-300 flex flex-col items-center text-center space-y-4">
                    <div class="p-4 bg-red-50 text-[#DC2626] rounded-full">
                        <!-- Fire icon -->
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Kebakaran</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Penyelamatan & penanganan kebakaran oleh Damkar.</p>
                </div>

                <!-- Card Kecelakaan -->
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-md hover:border-red-500/30 transition duration-300 flex flex-col items-center text-center space-y-4">
                    <div class="p-4 bg-orange-50 text-orange-600 rounded-full">
                        <!-- Car accident icon -->
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Kecelakaan</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Tabrakan lalu lintas atau kecelakaan industri berat.</p>
                </div>

                <!-- Card Medis -->
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-md hover:border-red-500/30 transition duration-300 flex flex-col items-center text-center space-y-4">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-full">
                        <!-- Heart/medical icon -->
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Darurat Medis</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Kondisi kesehatan mendesak, serangan jantung, ambulans.</p>
                </div>

                <!-- Card Keamanan -->
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-md hover:border-red-500/30 transition duration-300 flex flex-col items-center text-center space-y-4">
                    <div class="p-4 bg-blue-50 text-[#2563EB] rounded-full">
                        <!-- Shield/security icon -->
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Keamanan</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Pencurian, kekerasan, gangguan ketertiban publik.</p>
                </div>

                <!-- Card Bencana -->
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-slate-200 hover:shadow-md hover:border-red-500/30 transition duration-300 flex flex-col items-center text-center space-y-4">
                    <div class="p-4 bg-yellow-50 text-yellow-600 rounded-full">
                        <!-- Cloud/natural disaster icon -->
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Bencana Alam</h3>
                    <p class="text-xs text-slate-400 leading-relaxed">Banjir, gempa, tanah longsor, angin kencang.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 4: Pelaporan -->
    <section id="kategori-laporan" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="text-sm font-bold text-[#2563EB] uppercase tracking-wider">Layanan Publik</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Kategori Laporan Masyarakat</h2>
                <p class="text-slate-500">
                    Laporkan keluhan dan permasalahan fasilitas umum di sekitar Anda untuk ditindaklanjuti instansi.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-4">
                @foreach($kategoriPelaporan as $kategori)
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-center flex flex-col items-center justify-center space-y-2 hover:bg-[#2563EB]/5 hover:border-[#2563EB]/25 transition duration-200">
                        <!-- Check icon -->
                        <svg class="w-6 h-6 text-[#2563EB]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-xs font-bold text-slate-700">{{ $kategori->nama_kategori }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Section 5: Instansi -->
    <section id="instansi" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                <span class="text-sm font-bold text-[#DC2626] uppercase tracking-wider">Direktori</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900">Instansi Terdaftar</h2>
                <p class="text-slate-500">
                    Daftar instansi kedaruratan dan layanan masyarakat yang terhubung di sistem wilayah kami.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @forelse($instansiList as $instansi)
                    <div class="p-6 bg-white border border-slate-200 rounded-2xl shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="flex justify-between items-start">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    {{ $instansi->kategori_instansi }}
                                </span>
                                <span class="text-xs text-slate-400 font-semibold">{{ $instansi->wilayah }}</span>
                            </div>
                            <h3 class="font-bold text-slate-800 text-lg">{{ $instansi->nama_instansi }}</h3>
                            <p class="text-sm text-slate-500 truncate">{{ $instansi->alamat }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center p-8 bg-white border border-slate-200 rounded-2xl text-slate-400">
                        Belum ada instansi terdaftar di sistem.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <!-- Branding -->
                <div class="space-y-4 col-span-2">
                    <div class="flex items-center space-x-2">
                        <div class="p-1.5 bg-[#DC2626] rounded-lg text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white tracking-wider">SIP-DARURAT</span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed max-w-sm">
                        Sistem Informasi Layanan Darurat dan Pelaporan Masalah Publik Berbasis Lokasi di Wilayah Kota Kabupaten. Tanggap, responsif, dan akurat.
                    </p>
                </div>

                <!-- Links -->
                <div class="space-y-2">
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider">Tautan Cepat</h4>
                    <ul class="space-y-1 text-sm">
                        <li><a href="#cara-kerja" class="hover:text-white transition">Cara Kerja</a></li>
                        <li><a href="#layanan-darurat" class="hover:text-white transition">Layanan Darurat</a></li>
                        <li><a href="#kategori-laporan" class="hover:text-white transition">Pelaporan</a></li>
                        <li><a href="#instansi" class="hover:text-white transition">Instansi</a></li>
                    </ul>
                </div>

                <!-- Legal/Credits -->
                <div class="space-y-2">
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider">Hubungi</h4>
                    <p class="text-sm text-slate-400">
                        Email: support@sipdarurat.go.id<br>
                        Telp: 112 (Nasional)<br>
                        Gedung Balai Kota, Mataram, Lombok
                    </p>
                </div>
            </div>
            <div class="pt-8 mt-8 border-t border-slate-800 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} SIP-DARURAT. Hak Cipta Dilindungi Undang-Undang.
            </div>
        </div>
    </footer>

</body>
</html>
