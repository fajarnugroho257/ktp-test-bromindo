<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // id provinsi
        $provinsi_id = DB::table('provinsi')->pluck('id');

        foreach (range(1, 100) as $i) {
            DB::table('kabupaten')->insert([
                'provinsi_id' => $faker->randomElement($provinsi_id),
                'kabupaten_nama' => $faker->city,
                'kabupaten_code' => $faker->numberBetween(10, 99),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
