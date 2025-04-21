<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kabupaten extends Model
{
    use HasFactory;
    protected $table = 'kabupaten';
    protected $fillable = ['provinsi_id', 'kabupaten_nama', 'kabupaten_code'];
    public function ktp()
    {
        return $this->hasMany(Ktp::class, 'kabupaten_id', 'id');
    }
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id', 'id');
    }
    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, 'kecamatan_id', 'id');
    }
}
