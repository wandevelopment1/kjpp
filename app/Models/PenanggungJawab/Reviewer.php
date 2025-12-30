<?php

namespace App\Models\PenanggungJawab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
    use HasFactory;

    protected $table = 'penanggung_jawab_reviewers';

    protected $fillable = [
        'name',
        'no_mappi',
        'no_izin_penilai',
        'no_rmk',
    ];
}
