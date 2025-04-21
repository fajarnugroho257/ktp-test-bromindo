<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;
    protected $table = 'kecamatan';
    protected $fillable = ['kabupaten_id', 'kecamatan_nama', 'kecamatan_code'];
    public function ktp()
    {
        return $this->hasMany(Ktp::class, 'kecamatan_id', 'id');
    }
    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class, 'kabupaten_id', 'id');
    }
    public function kecamatan()
    {
        return $this->hasMany(Kecamatan::class, 'kecamatan_id', 'id');
    }
}
