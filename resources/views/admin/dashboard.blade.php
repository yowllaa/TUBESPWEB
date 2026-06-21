@extends('layouts.dashboard')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="space-y-8">
    <!-- Stat Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Masyarakat Terdaftar</span>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalPengguna }}</h3>
            </div>
            <div class="p-3 bg-blue-50 text-[#2563EB] rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Instansi Terdaftar</span>
                <h3 class="text-3xl font-extrabold text-[#2563EB]">{{ $totalInstansi }}</h3>
            </div>
            <div class="p-3 bg-red-50 text-[#DC2626] rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Total Laporan</span>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalLaporan }}</h3>
            </div>
            <div class="p-3 bg-slate-50 text-slate-700 rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Laporan Bulan Ini</span>
                <h3 class="text-3xl font-extrabold text-yellow-600">{{ $laporanBulanIni }}</h3>
            </div>
            <div class="p-3 bg-yellow-50 text-yellow-600 rounded-2xl">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid lg:grid-cols-12 gap-6">
        <!-- Chart 1: Laporan per Bulan (7 cols) -->
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div>
                <h3 class="text-base font-bold text-slate-800">Grafik Laporan per Bulan</h3>
                <p class="text-xs text-slate-400">Statistik kuantitas laporan pengaduan masyarakat sepanjang tahun ini.</p>
            </div>
            <div class="h-80">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Laporan per Kategori (5 cols) -->
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div>
                <h3 class="text-base font-bold text-slate-800">Distribusi Kategori Laporan</h3>
                <p class="text-xs text-slate-400">Statistik sebaran kategori permasalahan pengaduan yang masuk.</p>
            </div>
            <div class="h-80 flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Render Charts JavaScript -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // 1. Monthly Chart
        const monthCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyData = @json($chartBulanData);
        
        new Chart(monthCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(monthlyData),
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: Object.values(monthlyData),
                    backgroundColor: '#2563EB',
                    borderRadius: 8,
                    hoverBackgroundColor: '#1D4ED8',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#F1F5F9' },
                        ticks: { stepSize: 1 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // 2. Category Chart
        const catCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryData = @json($categoriesData);
        const labels = Object.keys(categoryData);
        const dataValues = Object.values(categoryData);

        // Generate nice palettes
        const colors = [
            '#DC2626', '#2563EB', '#10B981', '#F59E0B', 
            '#8B5CF6', '#EC4899', '#64748B', '#06B6D4'
        ];

        if (labels.length === 0) {
            // Draw empty state on canvas
            catCtx.font = "14px sans-serif";
            catCtx.fillStyle = "#94A3B8";
            catCtx.textAlign = "center";
            catCtx.fillText("Belum ada data laporan", 150, 150);
        } else {
            new Chart(catCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: dataValues,
                        backgroundColor: colors.slice(0, labels.length),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, font: { size: 10 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
