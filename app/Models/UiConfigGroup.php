<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class UiConfigGroup extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->slug = $model->generateUniqueSlug($model->title);
        });
    }

    protected function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $i = 1;

        while (static::where('slug', $slug)
            ->where('id', '!=', $this->id) // tidak bentrok saat update data sendiri
            ->exists()) {
            $slug = $originalSlug . '_' . $i++;
        }

        return $slug;
    }

    public function configs()
    {
        return $this->hasMany(UiConfig::class);
    }

}
