<?php
// Filename: pages/ringkasan.php
// Version: 3.1
?>
<section id="page-ringkasan">
    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Perjawatan Keseluruhan -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-slate-500 text-sm font-medium">Perjawatan Keseluruhan</h3>
                <p class="text-3xl font-bold text-slate-800 mt-2">542,764</p>
                <p class="text-xs text-slate-400 mt-1">+1,200 dari bulan lepas</p>
            </div>
            <div class="bg-indigo-100 p-3 rounded-full">
                <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
        </div>
        <!-- Pengisian Keseluruhan -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-slate-500 text-sm font-medium">Pengisian Keseluruhan</h3>
                <p class="text-3xl font-bold text-slate-800 mt-2">511,747</p>
                <p class="text-xs text-teal-500 font-semibold mt-1">▲ 0.1% dari bulan lepas</p>
            </div>
             <div class="bg-teal-100 p-3 rounded-full">
                <svg class="w-7 h-7 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <!-- Kekosongan -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-slate-500 text-sm font-medium">Kekosongan</h3>
                <p class="text-3xl font-bold text-rose-600 mt-2">31,017</p>
                <p class="text-xs text-rose-500 font-semibold mt-1">▼ 500 dari bulan lepas</p>
            </div>
             <div class="bg-rose-100 p-3 rounded-full">
                <svg class="w-7 h-7 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
            </div>
        </div>
        <!-- Peratus Pengisian -->
        <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-slate-500 text-sm font-medium">Peratus Pengisian</h3>
                <p class="text-3xl font-bold text-slate-800 mt-2">94.29%</p>
                <p class="text-xs text-slate-400 mt-1">Sasaran: 95%</p>
            </div>
             <div class="bg-amber-100 p-3 rounded-full">
                <svg class="w-7 h-7 text-amber-500"  fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-8">
             <!-- Tren Pengisian & Kekosongan -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
                <h3 class="text-lg font-semibold mb-4 text-slate-700">Tren Pengisian & Kekosongan (6 Bulan)</h3>
                <div class="h-80">
                    <canvas id="trenChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Pengisian Mengikut Kumpulan Perkhidmatan -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
                <h3 class="text-lg font-semibold mb-4 text-slate-700">Pengisian Mengikut Kumpulan</h3>
                <div class="h-64">
                    <canvas id="kumpulanChart"></canvas>
                </div>
            </div>
            <!-- Top 5 Kekosongan -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
                <h3 class="text-lg font-semibold mb-4 text-slate-700">5 Agensi Dengan Kekosongan Tertinggi</h3>
                <ul class="space-y-3">
                    <li class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">Sekolah</span>
                        <span class="font-bold text-rose-600">25,613</span>
                    </li>
                    <li class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">Kementerian</span>
                        <span class="font-bold text-rose-600">1,954</span>
                    </li>
                    <li class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">IPGM</span>
                        <span class="font-bold text-rose-600">1,657</span>
                    </li>
                    <li class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">Kolej Matrikulasi</span>
                        <span class="font-bold text-rose-600">770</span>
                    </li>
                    <li class="flex justify-between items-center text-sm">
                        <span class="text-slate-600">PPD</span>
                        <span class="font-bold text-rose-600">498</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Carta Tren Pengisian & Kekosongan (Line Chart)
    const trenCanvas = document.getElementById('trenChart');
    if (trenCanvas) {
        new Chart(trenCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mac', 'Apr', 'Mei', 'Jun'],
                datasets: [
                    {
                        label: 'Pengisian',
                        data: [510100, 510250, 510800, 511150, 511500, 511747],
                        borderColor: 'rgba(20, 184, 166, 1)',
                        backgroundColor: 'rgba(20, 184, 166, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Kekosongan',
                        data: [32664, 32514, 31964, 31614, 31264, 31017],
                        borderColor: 'rgba(225, 29, 72, 1)',
                        backgroundColor: 'rgba(225, 29, 72, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: false }
                }
            }
        });
    }

    // 2. Carta Kumpulan Perkhidmatan (Doughnut Chart)
    const kumpulanCanvas = document.getElementById('kumpulanChart');
    if (kumpulanCanvas) {
        new Chart(kumpulanCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Pendidikan', 'Pelaksana', 'P&P', 'Lain-lain'],
                datasets: [{
                    label: 'Bilangan Pengisian',
                    data: [432319, 77794, 1499, 148],
                    backgroundColor: [
                        'rgba(79, 70, 229, 0.8)',
                        'rgba(20, 184, 166, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(107, 114, 128, 0.8)'
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                },
                cutout: '60%'
            }
        });
    }
});
</script>

