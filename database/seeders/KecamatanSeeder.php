<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $data_kecamatan = [
            'Bandongan',
            'Borobudur',
            'Candimulyo',
            'Dukun',
            'Grabag',
            'Kajoran',
            'Kaliangkrik',
            'Mertoyudan',
            'Mungkid',
            'Muntilan',
            'Ngablak',
            'Ngluwar',
            'Pakis',
            'Salam',
            'Salaman',
            'Sawangan',
            'Secang',
            'Srumbung',
            'Tegalrejo',
            'Tempuran',
            'Windusari',
            'Cacaban',
            'Ungaran Barat',
            'Ungaran Timur',
            'Bergas',
            'Pringapus',
            'Bawen',
            'Bringin',
            'Tuntang',
            'Pabelan',
            'Bancak',
            'Suruh',
            'Susukan',
            'Kaliwungu',
            'Tengaran',
            'Getasan',
            'Banyubiru',
            'Sumowono',
            'Ambarawa',
            'Jambu',
            'Bandungan',
            'Bansari',
            'Bejen',
            'Bulu',
            'Candiroto',
            'Gemawang',
            'Jumo',
            'Kaloran',
            'Kandangan',
            'Kedu',
            'Kledung',
            'Kranggan',
            'Ngadirejo',
            'Parakan',
            'Pringsurat',
            'Selopampang',
            'Temanggung',
            'Tembarak',
            'Tlogomulyo',
            'Tretep',
            'Wonoboyo',
            'Banyumanik',
            'Candisari',
            'Gajahmungkur',
            'Gayamsari',
            'Genuk',
            'Gunungpati',
            'Mijen',
            'Ngaliyan',
            'Pedurungan',
            'Semarang Barat',
            'Semarang Selatan',
            'Semarang Tengah',
            'Semarang Timur',
            'Semarang Utara',
            'Tembalang',
            'Tugu',
        ];
        // id kabupaten
        $kabupaten_id = DB::table('kabupaten')->pluck('id');

        foreach (range(1, 200) as $i) {
            DB::table('kecamatan')->insert([
                'kabupaten_id' => $faker->randomElement($kabupaten_id),
                'kecamatan_nama' => $faker->randomElement($data_kecamatan),
                'kecamatan_code' => $faker->numberBetween(10, 99),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
