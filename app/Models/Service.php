<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['title', 'slug', 'short_description', 'content', 'icon', 'meta_title', 'meta_description', 'keywords', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
