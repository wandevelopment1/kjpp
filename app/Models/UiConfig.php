<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UiConfig extends Model
{
    protected $guarded = [];

    public function group()
    {
        return $this->belongsTo(UiConfigGroup::class, 'ui_config_group_id');
    }
}
