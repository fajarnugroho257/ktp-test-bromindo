<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    use HasFactory;
    protected $table = 'pekerjaan';
    protected $fillable = ['pekerjaan_nama'];

    public function ktp()
    {
        $this->hasMany(Ktp::class, 'pekerjaan_id', 'id');
    }
}
