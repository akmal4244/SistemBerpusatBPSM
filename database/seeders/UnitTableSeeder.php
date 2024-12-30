<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Unit::insert([
            ['id' => 1, 'title' => 'Unit Pembangunan Dasar Sumber Manusia Guru 1 (G1)', 'department_id' => 1],
            ['id' => 2, 'title' => 'Unit Pembangunan Dasar Sumber Manusia Guru 2 (G2)', 'department_id' => 1],
            ['id' => 3, 'title' => 'Unit Dasar Sumber Manusia (D)', 'department_id' => 1],
            ['id' => 4, 'title' => 'Unit Perancangan Strategik Sumber Manusia (S)', 'department_id' => 1],
            ['id' => 5, 'title' => 'Unit Pembangunan Dasar Sumber Manusia Bukan Guru (BG)', 'department_id' => 1],
            ['id' => 6, 'title' => 'Unit Tadbir Urus Dasar Sumber Manusia 1 (TU1)', 'department_id' => 1],
            ['id' => 7, 'title' => 'Unit Tadbir Urus Dasar Sumber Manusia 2 (TU2)', 'department_id' => 1],
            ['id' => 8, 'title' => 'Unit Penyelarasan dan Pengurusan Maklumat (UPPM)', 'department_id' => 1],
            ['id' => 9, 'title' => 'Unit Pengukuhan Perjawatan', 'department_id' => 2],
            ['id' => 10, 'title' => 'Unit Pengurusan Waran Perjawatan', 'department_id' => 2],
            ['id' => 11, 'title' => 'Unit Inspektorat', 'department_id' => 2],
            ['id' => 12, 'title' => 'Unit Perancangan dan Dasar Latihan (DL)', 'department_id' => 3],
            ['id' => 13, 'title' => 'Unit Latihan dan Perkhidmatan (LDP)', 'department_id' => 3],
            ['id' => 14, 'title' => 'Unit Induksi', 'department_id' => 3],
            ['id' => 15, 'title' => 'Unit Penilaian Kompetensi (PK)', 'department_id' => 3],
            ['id' => 16, 'title' => 'Unit Peperiksaan Perkhidmatan (PP)', 'department_id' => 3],
            ['id' => 17, 'title' => 'Unit Pembangunan Prestasi', 'department_id' => 3],
            ['id' => 18, 'title' => 'Unit Ambilan Guru (AG)', 'department_id' => 4],
            ['id' => 19, 'title' => 'Unit Guru (G)', 'department_id' => 4],
            ['id' => 20, 'title' => 'Unit Saraan, Kemudahan dan Hubungan Kesatuan (SK)', 'department_id' => 4],
            ['id' => 21, 'title' => 'Unit Bukan Guru (BG)', 'department_id' => 4],
            ['id' => 22, 'title' => 'Unit Buku Perkhidmatan Kerajaan', 'department_id' => 4],
            ['id' => 23, 'title' => 'Unit Naik Pangkat Siswazah 1', 'department_id' => 5],
            ['id' => 24, 'title' => 'Unit Naik Pangkat Siswazah 2 (TC)', 'department_id' => 5],
            ['id' => 25, 'title' => 'Unit Naik Pangkat Bukan Guru', 'department_id' => 5],
            ['id' => 26, 'title' => 'Unit Naik Pangkat Bukan Siswazah', 'department_id' => 5],
            ['id' => 27, 'title' => 'Unit Pembangunan Kepimpinan Pendidikan', 'department_id' => 5],
            ['id' => 28, 'title' => 'Unit Pentadbiran', 'department_id' => 6],
            ['id' => 29, 'title' => 'Unit Pengurusan Rekod', 'department_id' => 6],
            ['id' => 30, 'title' => 'Unit Perolehan dan Aset Stor', 'department_id' => 6],
            ['id' => 31, 'title' => 'Unit Kewangan', 'department_id' => 6],
            ['id' => 32, 'title' => 'Unit Perkhidmatan', 'department_id' => 6],
            ['id' => 33, 'title' => 'Unit Teknikal dan ICT', 'department_id' => 6],
        ]);
    }
}
