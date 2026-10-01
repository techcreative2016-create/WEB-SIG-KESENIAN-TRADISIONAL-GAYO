<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $table = 'dokumentasis';

    protected $fillable = [
        'judul',
        'tipe',
        'deskripsi',
        'tanggal_dokumen',
        'file_path',
        'thumbnail',
        'url_eksternal',
        'status',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
    ];

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Hanya dokumentasi yang aktif (untuk publik).
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    /**
     * Filter berdasarkan tipe (Foto, Video, Naskah, Audio).
     */
    public function scopeByTipe($query, string $tipe)
    {
        return $query->where('tipe', $tipe);
    }

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * URL thumbnail: jika ada, pakai dari storage. Jika tidak, gunakan placeholder icon.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return null;
    }

    /**
     * URL file yang diupload.
     */
    public function getFileUrlAttribute(): ?string
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    /**
     * Material icon yang sesuai berdasarkan tipe.
     */
    public function getIconAttribute(): string
    {
        return match($this->tipe) {
            'Foto'   => 'photo_camera',
            'Video'  => 'videocam',
            'Naskah' => 'history_edu',
            'Audio'  => 'music_note',
            default  => 'article',
        };
    }
}
