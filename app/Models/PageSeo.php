<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageSeo extends Model
{
    protected $fillable = ['page_name', 'slug', 'meta_title', 'meta_description', 'keywords', 'og_image'];

    public static function for(string $slug): ?self
    {
        return static::where('slug', $slug)->first();
    }
}
