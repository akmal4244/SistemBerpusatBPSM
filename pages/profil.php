<?php
// Filename: pages/profil.php
// Version: 1.0
?>
<section id="page-profil">
    <h1 class="text-3xl font-bold text-slate-800 mb-6">Profil Pegawai</h1>
    
    <!-- Search Bar -->
    <div class="mb-6">
        <label for="search-pegawai" class="sr-only">Cari Pegawai</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <input id="search-pegawai" name="search-pegawai" class="block w-full pl-10 pr-3 py-2 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-500 focus:outline-none focus:placeholder-slate-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Cari mengikut Nama atau No. Kad Pengenalan..." type="search">
        </div>
    </div>

    <!-- Profile Content Placeholder -->
    <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Profile Picture and Basic Info -->
            <div class="lg:col-span-1 flex flex-col items-center text-center">
                <img class="h-32 w-32 rounded-full object-cover" src="https://placehold.co/256x256/E2E8F0/475569?text=Gambar" alt="Gambar Profil">
                <h2 class="mt-4 text-xl font-bold text-slate-800">Suresh Kumar</h2>
                <p class="text-sm text-slate-500">Pegawai Perkhidmatan Pendidikan DG41</p>
            </div>

            <!-- Detailed Info -->
            <div class="lg:col-span-3">
                <h3 class="text-lg font-semibold text-slate-700 border-b pb-2 mb-4">Maklumat Peribadi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="font-medium text-slate-500">No. Kad Pengenalan</p>
                        <p class="text-slate-800">790110-14-0123</p>
                    </div>
                    <div>
                        <p class="font-medium text-slate-500">Umur</p>
                        <p class="text-slate-800">46 Tahun</p> 
                    </div>
                    <div>
                        <p class="font-medium text-slate-500">Jantina</p>
                        <p class="text-slate-800">Lelaki</p>
                    </div>
                    <div>
                        <p class="font-medium text-slate-500">Kaum</p>
                        <p class="text-slate-800">India</p>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-slate-700 border-b pb-2 mt-8 mb-4">Maklumat Perkhidmatan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="font-medium text-slate-500">Agensi Semasa</p>
                        <p class="text-slate-800">Sekolah Menengah Shah Alam</p>
                    </div>
                     <div>
                        <p class="font-medium text-slate-500">Jawatan</p>
                        <p class="text-slate-800">Guru Akademik</p>
                    </div>
                    <div>
                        <p class="font-medium text-slate-500">Tarikh Mula Berkhidmat</p>
                        <p class="text-slate-800">15 Mac 2005</p>
                    </div>
                     <div>
                        <p class="font-medium text-slate-500">Tempoh Berkhidmat</p>
                        <p class="text-slate-800">20 Tahun, 7 Bulan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
