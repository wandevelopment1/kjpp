<?php

namespace App\Models\PenggunaLaporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nama extends Model
{
    use HasFactory;

    protected $table = 'pengguna_laporan_nama';

    protected $fillable = ['name'];
}
