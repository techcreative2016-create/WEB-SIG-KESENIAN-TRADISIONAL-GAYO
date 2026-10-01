<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileHeroBanner extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'tag',
        'image_path',
        'stats',
        'section_title',
        'description',
        'info_rows',
        'quote',
        'tips_title',
        'tips',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'stats' => 'array',
        'info_rows' => 'array',
        'tips' => 'array',
        'is_active' => 'boolean',
    ];
}
