<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Traits\LogsActivity;

class Galeri extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'galeri';

    protected $fillable = [
        'galeri_able_id',
        'galeri_able_type',
        'judul',
        'path_file',
        'tipe_media',
        'urutan',
        'is_utama',
    ];

    protected $casts = [
        'is_utama' => 'boolean',
        'urutan'   => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * URL publik file media.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path_file);
    }

    /**
     * Cek apakah media ini adalah video.
     */
    public function getIsVideoAttribute(): bool
    {
        return $this->tipe_media === 'video';
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeFoto($query)
    {
        return $query->where('tipe_media', 'foto');
    }

    public function scopeVideo($query)
    {
        return $query->where('tipe_media', 'video');
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Polymorphic: relasi balik ke model pemilik (Sanggar atau Toko).
     * Satu entri galeri bisa dimiliki oleh Sanggar ATAU Toko.
     */
    public function galeriAble(): MorphTo
    {
        return $this->morphTo();
    }
}
