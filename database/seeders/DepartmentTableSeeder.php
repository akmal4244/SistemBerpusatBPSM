<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::insert([
            ['id' => 1, 'title' => 'Cawangan Dasar', 'title2' => 'D'],
            ['id' => 2, 'title' => 'Cawangan Pembangunan Organisasi', 'title2' => 'PO'],
            ['id' => 3, 'title' => 'Cawangan Latihan dan Kompetensi', 'title2' => 'L'],
            ['id' => 4, 'title' => 'Cawangan Perkhidmatan', 'title2' => 'K'],
            ['id' => 5, 'title' => 'Cawangan Naik Pangkat', 'title2' => 'P'],
            ['id' => 6, 'title' => 'Cawangan Pentadbiran dan Kewangan', 'title2' => 'TW'],

        ]);

    }
}
