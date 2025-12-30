<?php

// app/Services/UiConfigService.php

namespace App\Services;

use App\Models\UiConfig;

class UiConfigService
{
   public function getValueByGroupSlugAndKey(string $slug, string $key): ?string
{
    return UiConfig::whereHas('group', function ($q) use ($slug) {
            $q->where('slug', $slug);
        })
        ->where('key', $key)
        ->value('value');
}

}
