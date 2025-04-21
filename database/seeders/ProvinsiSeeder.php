<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('provinsi')->insert([
            ['provinsi_nama' => 'Aceh', 'provinsi_code' => '11'],
            ['provinsi_nama' => 'Sumatera Utara', 'provinsi_code' => '12'],
            ['provinsi_nama' => 'Sumatera Barat', 'provinsi_code' => '13'],
            ['provinsi_nama' => 'Riau', 'provinsi_code' => '14'],
            ['provinsi_nama' => 'Jambi', 'provinsi_code' => '15'],
            ['provinsi_nama' => 'Sumatera Selatan', 'provinsi_code' => '16'],
            ['provinsi_nama' => 'Bengkulu', 'provinsi_code' => '17'],
            ['provinsi_nama' => 'Lampung', 'provinsi_code' => '18'],
            ['provinsi_nama' => 'Bangka Belitung', 'provinsi_code' => '19'],
            ['provinsi_nama' => 'Kepulauan Riau', 'provinsi_code' => '21'],
            ['provinsi_nama' => 'DKI Jakarta', 'provinsi_code' => '31'],
            ['provinsi_nama' => 'Jawa Barat', 'provinsi_code' => '32'],
            ['provinsi_nama' => 'Jawa Tengah', 'provinsi_code' => '33'],
            ['provinsi_nama' => 'DI Yogyakarta', 'provinsi_code' => '34'],
            ['provinsi_nama' => 'Jawa Timur', 'provinsi_code' => '35'],
            ['provinsi_nama' => 'Banten', 'provinsi_code' => '36'],
            ['provinsi_nama' => 'Bali', 'provinsi_code' => '51'],
            ['provinsi_nama' => 'Nusa Tenggara Barat', 'provinsi_code' => '52'],
            ['provinsi_nama' => 'Nusa Tenggara Timur', 'provinsi_code' => '53'],
            ['provinsi_nama' => 'Kalimantan Barat', 'provinsi_code' => '61'],
            ['provinsi_nama' => 'Kalimantan Tengah', 'provinsi_code' => '62'],
            ['provinsi_nama' => 'Kalimantan Selatan', 'provinsi_code' => '63'],
            ['provinsi_nama' => 'Kalimantan Timur', 'provinsi_code' => '64'],
            ['provinsi_nama' => 'Kalimantan Utara', 'provinsi_code' => '65'],
            ['provinsi_nama' => 'Sulawesi Utara', 'provinsi_code' => '71'],
            ['provinsi_nama' => 'Sulawesi Tengah', 'provinsi_code' => '72'],
            ['provinsi_nama' => 'Sulawesi Selatan', 'provinsi_code' => '73'],
            ['provinsi_nama' => 'Sulawesi Tenggara', 'provinsi_code' => '74'],
            ['provinsi_nama' => 'Gorontalo', 'provinsi_code' => '75'],
            ['provinsi_nama' => 'Sulawesi Barat', 'provinsi_code' => '76'],
            ['provinsi_nama' => 'Maluku', 'provinsi_code' => '81'],
            ['provinsi_nama' => 'Maluku Utara', 'provinsi_code' => '82'],
            ['provinsi_nama' => 'Papua', 'provinsi_code' => '91'],
            ['provinsi_nama' => 'Papua Barat', 'provinsi_code' => '92'],
        ]);
    }
}
