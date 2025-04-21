<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ktp extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $table = 'ktp';
    protected $primaryKey = 'ktp_nik';
    protected $fillable = [
        'ktp_nik',
        'ktp_nama',
        'ktp_tempat_lahir',
        'ktp_tgl_lahir',
        'ktp_umur',
        'ktp_darah',
        'ktp_dusun',
        'ktp_rt',
        'ktp_rw',
        'pekerjaan_id',
        'kelurahan_id',
        'kecamatan_id',
        'kabupaten_id',
        'provinsi_id',
        'ktp_agama',
        'ktp_perkawinan',
        'ktp_negara',
        'ktp_path',
        'ktp_berlaku',
        'ktp_dibuat',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id', 'id');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id', 'id');
    }
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id', 'id');
    }
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id', 'id');
    }
    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'pekerjaan_id', 'id');
    }
}
