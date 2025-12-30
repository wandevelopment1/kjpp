<?php

namespace App\Models\PenggunaLaporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pt extends Model
{
    use HasFactory;

    protected $table = 'pengguna_laporan_pts';

    protected $fillable = [
        'name',
        'alamat',
        'kab_kota',
        'provinsi',
        'kode_pos',
    ];
}
