<?php

namespace App\Models\PenanggungJawab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'penanggung_jawab_companies';

    protected $fillable = ['name'];
}
