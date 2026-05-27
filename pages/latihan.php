<?php
// Filename: pages/latihan.php
// Version: 1.2
?>
<section id="page-latihan">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Prestasi & Analisis Latihan</h1>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform transform hover:scale-105">
            <h3 class="text-gray-500 text-sm font-medium">Jumlah Kursus Dijalankan</h3>
            <p class="text-3xl font-bold text-blue-600 mt-2">1,204</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform transform hover:scale-105">
            <h3 class="text-gray-500 text-sm font-medium">Jumlah Penyertaan</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">35,670</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform transform hover:scale-105">
            <h3 class="text-gray-500 text-sm font-medium">Purata Jam Latihan / Pegawai</h3>
            <p class="text-3xl font-bold text-teal-600 mt-2">32.5 Jam</p>
            <p class="text-xs text-gray-400 mt-1">Sasaran: 40 Jam</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform transform hover:scale-105">
            <h3 class="text-gray-500 text-sm font-medium">Kadar Kehadiran</h3>
            <p class="text-3xl font-bold text-orange-500 mt-2">95.8%</p>
        </div>
    </div>
    
    <!-- Jadual Prestasi Kursus -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-4 text-gray-700">Senarai Kursus & Penyertaan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kursus</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kumpulan Sasar</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bilangan Peserta</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Data Contoh -->
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Kursus Kepimpinan Abad Ke-21</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Pengurusan & Profesional</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">150</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Bengkel Pengurusan Kewangan (AKP)</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Pelaksana</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">320</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Seminar Integriti & Tadbir Urus</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Semua Kumpulan</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">500</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                           <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Berjalan</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

