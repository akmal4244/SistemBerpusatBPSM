<?php
// Filename: pages/oku.php
// Version: 1.2
?>
<section id="page-oku">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Status Kepegawaian OKU & Orang Asli</h1>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform transform hover:scale-105">
            <h3 class="text-gray-500 text-sm font-medium">Bilangan Pegawai OKU</h3>
            <p class="text-3xl font-bold text-indigo-600 mt-2">650</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform transform hover:scale-105">
            <h3 class="text-gray-500 text-sm font-medium">Peratus Penyertaan OKU</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">0.46%</p>
            <p class="text-xs text-gray-400 mt-1">Sasaran: 1%</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md text-center transition-transform transform hover:scale-105">
            <h3 class="text-gray-500 text-sm font-medium">Bilangan Pegawai Orang Asli</h3>
            <p class="text-3xl font-bold text-green-600 mt-2">325</p>
        </div>
    </div>
    
    <!-- Jadual Pecahan Mengikut Agensi -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-semibold mb-4 text-gray-700">Pecahan Mengikut Agensi/Negeri Terpilih</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agensi / Negeri</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bilangan Pegawai OKU</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bilangan Pegawai Orang Asli</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Data Contoh -->
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Jabatan Pendidikan Negeri Pahang</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">58</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">112</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Jabatan Pendidikan Negeri Perak</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">45</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">95</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Jabatan Pendidikan Negeri Kelantan</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">32</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">43</td>
                    </tr>
                    <tr class="hover:bg-gray-100">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">Jabatan Pendidikan Negeri Selangor</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">71</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">15</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

