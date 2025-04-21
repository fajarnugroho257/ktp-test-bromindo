<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;
    protected $table = 'provinsi';
    protected $fillable = ['provinsi_nama', 'provinsi_code'];

    public function ktp()
    {
        return $this->hasMany(Ktp::class, 'provinsi_id', 'id');
    }
    public function provinsi()
    {
        return $this->hasMany(Kabupaten::class, 'kabupaten_id', 'id');
    }
}
