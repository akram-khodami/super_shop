<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class BaseModel extends Model
{
    public static function generateUniqueSlug(string $name, string $column = 'slug', ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $query = static::where($column, 'LIKE', "{$slug}%");

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $existingSlugs = $query->pluck($column)->toArray();

        if (!in_array($slug, $existingSlugs)) {
            return $slug;
        }

        $counter = 1;
        while (in_array("{$slug}-{$counter}", $existingSlugs)) {
            $counter++;
        }

        return "{$slug}-{$counter}";
    }
}
