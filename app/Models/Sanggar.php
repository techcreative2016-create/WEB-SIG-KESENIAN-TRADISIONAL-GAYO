<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use App\Traits\LogsActivity;

class Sanggar extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'sanggars';

    protected $fillable = [
        'nama_sanggar',
        'slug',
        'kategori',
        'deskripsi',
        'alamat',
        'kabupaten',
        'kecamatan',
        'latitude',
        'longitude',
        'no_telepon',
        'email',
        'instagram',
        'status',
        'foto_utama',
        'user_id',
        'nama_pimpinan',
        'peran_pimpinan',
        'nama_koreografer',
        'peran_koreografer',
    ];

    protected $casts = [
        'latitude'  => 'float',     // Cast ke float untuk akurasi koordinat
        'longitude' => 'float',
    ];

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    /**
     * Tampilkan URL foto utama atau placeholder jika kosong.
     */
    public function getFotoUtamaUrlAttribute(): string
    {
        return $this->foto_utama
            ? asset('storage/' . $this->foto_utama)
            : asset('images/placeholder-sanggar.jpg');
    }

    /**
     * Cek apakah sanggar sedang aktif.
     */
    public function getIsAktifAttribute(): bool
    {
        return $this->status === 'Aktif';
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Filter sanggar berdasarkan kategori.
     */
    public function scopeByKategori($query, string $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Filter sanggar berdasarkan kabupaten.
     */
    public function scopeByKabupaten($query, string $kabupaten)
    {
        return $query->where('kabupaten', $kabupaten);
    }

    /**
     * Hanya ambil sanggar yang aktif.
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------



    /**
     * Relasi ke pembuat (Admin).
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Galeri foto/video menggunakan polymorphic relationship.
     */
    public function galeri(): MorphMany
    {
        return $this->morphMany(Galeri::class, 'galeri_able')
                    ->orderBy('urutan');
    }

    /**
     * Hanya foto utama galeri.
     */
    public function fotoUtamaGaleri(): MorphMany
    {
        return $this->morphMany(Galeri::class, 'galeri_able')
                    ->where('is_utama', true)
                    ->where('tipe_media', 'foto');
    }

    // -------------------------------------------------------------------------
    // Boots / Hooks
    // -------------------------------------------------------------------------

    protected static function boot(): void
    {
        parent::boot();

        // Auto-generate slug dari nama sanggar saat dibuat
        static::creating(function (Sanggar $sanggar) {
            if (empty($sanggar->slug)) {
                $sanggar->slug = Str::slug($sanggar->nama_sanggar);
            }
        });
    }

    // -------------------------------------------------------------------------
    // Helpers untuk Peta (GIS)
    // -------------------------------------------------------------------------

    /**
     * Kembalikan array koordinat untuk Google Maps marker.
     */
    public function toMapMarker(): array
    {
        return [
            'id'          => $this->id,
            'nama'        => $this->nama_sanggar,
            'slug'        => $this->slug,
            'kategori'    => $this->kategori,
            'kabupaten'   => $this->kabupaten,
            'status'      => $this->status,
            'lat'         => (float) $this->latitude,
            'lng'         => (float) $this->longitude,
            'alamat'      => $this->alamat,
            'foto'        => $this->foto_utama_url,
            'detail_url'  => url('/peta#sanggar-' . $this->slug),
            'tipe'        => 'sanggar',  // Digunakan untuk membedakan ikon marker
        ];
    }
}
