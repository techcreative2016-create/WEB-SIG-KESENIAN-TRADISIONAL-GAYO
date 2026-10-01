<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

class Berita extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'beritas';

    protected $fillable = [
        'admin_id',
        'judul',
        'kategori',
        'konten',
        'thumbnail',
        'status',
    ];

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail)
            : asset('images/placeholder-berita.jpg');
    }
}
