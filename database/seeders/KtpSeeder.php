<?php

namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class KtpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        //
        foreach (range(1, 10000) as $i) {
            // pekerjaan
            $pekerjaan = DB::table('pekerjaan')->inRandomOrder()->first();
            // kelurahan
            $kelurahan = DB::table('kelurahan')->inRandomOrder()->first();
            //kecamatan
            $kecamatan = DB::table('kecamatan')->where('id', $kelurahan->kecamatan_id)->first();
            // kabupaten
            $kabupaten = DB::table('kabupaten')->where('id', $kecamatan->kabupaten_id)->first();
            // provinsi
            $provinsi = DB::table('provinsi')->where('id', $kabupaten->provinsi_id)->first();
            $tgl_lahir = $faker->dateTimeBetween('-60 years', '-17 years')->format('Y-m-d');
            // kode
            $split = explode('-', $tgl_lahir);
            $kode_tgl_lahir = $split[2] . $split[1] . substr($split[0], -2);
            //lokasi
            $kode_lokasi = $provinsi->provinsi_code . $kabupaten->kabupaten_code . $kecamatan->kecamatan_code;
            //
            $urutan = DB::table('ktp')
                ->select('ktp_nik', DB::raw('SUBSTRING(ktp_nik, 1, 6) AS kode'), DB::raw('SUBSTRING(ktp_nik, -4, 4) AS urut'))
                ->whereRaw('SUBSTRING(ktp_nik, 1, 12) = ?', [$kode_lokasi . $kode_tgl_lahir])
                ->orderBy(DB::raw('SUBSTRING(ktp_nik, -4, 4)'), 'DESC')
                ->first();
            if (!empty($urutan)) {
                $terakhir = (int) $urutan->urut;
                $urut_baru = $terakhir + 1;
                $format_urutan = str_pad($urut_baru, 4, '0', STR_PAD_LEFT);
            } else {
                $format_urutan = '0001';
            }
            $nik_kode = $kode_lokasi . $kode_tgl_lahir . $format_urutan;
            //
            DB::table('ktp')->insert([
                'ktp_nik' => $nik_kode,
                'ktp_nama' => $faker->name,
                'ktp_tempat_lahir' => $kabupaten->kabupaten_nama,
                'ktp_tgl_lahir' => $tgl_lahir,
                'ktp_umur' => Carbon::parse($tgl_lahir)->age,
                'ktp_jk' => $faker->randomElement(['L', 'P']),
                'ktp_darah' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                'ktp_dusun' => $kecamatan->kecamatan_nama,
                'ktp_rt' => $faker->randomElement(['01', '02', '03', '04', '05']),
                'ktp_rw' => $faker->randomElement(['01', '02', '03', '04', '05']),
                'pekerjaan_id' => $pekerjaan->id,
                'kelurahan_id' => $kelurahan->id,
                'kecamatan_id' => $kecamatan->id,
                'kabupaten_id' => $kabupaten->id,
                'provinsi_id' => $provinsi->id,
                'ktp_agama' => $faker->randomElement(['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu']),
                'ktp_perkawinan' => $faker->randomElement(['Belum kawin', 'Kawin', 'Cerai hidup', 'Cerai mati', 'Kawin belum tercatat']),
                'ktp_negara' => $faker->randomElement(['WNI', 'WNA']),
                'ktp_path' => 'foto/default.png',
                'ktp_dibuat' => date('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
