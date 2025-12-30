<?php

namespace App\Models\PenggunaLaporan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPengguna extends Model
{
    use HasFactory;

    protected $table = 'pengguna_laporan_jenis_pengguna';

    protected $fillable = ['name'];
}
