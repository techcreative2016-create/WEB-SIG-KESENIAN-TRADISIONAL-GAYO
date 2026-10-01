<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use App\Traits\LogsActivity;

class Toko extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'tokos';

    protected $fillable = [
        'nama_toko',
        'slug',
        'jenis_produk',
        'deskripsi',
        'alamat',
        'kabupaten',
        'kecamatan',
        'latitude',
        'longitude',
        'no_telepon',
        'jam_buka',
        'status',
        'foto_utama',
        'user_id',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    public function getFotoUtamaUrlAttribute(): string
    {
        return $this->foto_utama
            ? asset('storage/' . $this->foto_utama)
            : asset('images/placeholder-toko.jpg');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeByJenisProduk($query, string $jenis)
    {
        return $query->where('jenis_produk', $jenis);
    }

    public function scopeByKabupaten($query, string $kabupaten)
    {
        return $query->where('kabupaten', $kabupaten);
    }

    public function scopeBuka($query)
    {
        return $query->where('status', 'Buka');
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * Galeri foto/video Toko menggunakan polymorphic relationship.
     * Toko dan Sanggar berbagi satu tabel galeri.
     */
    public function galeri(): MorphMany
    {
        return $this->morphMany(Galeri::class, 'galeri_able')
                    ->orderBy('urutan');
    }

    /**
     * Relasi ke pembuat (Admin).
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // -------------------------------------------------------------------------
    // Boot
    // -------------------------------------------------------------------------

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Toko $toko) {
            if (empty($toko->slug)) {
                $toko->slug = Str::slug($toko->nama_toko);
            }
        });
    }

    // -------------------------------------------------------------------------
    // Helper untuk Peta (GIS)
    // -------------------------------------------------------------------------

    public function toMapMarker(): array
    {
        return [
            'id'         => $this->id,
            'nama'       => $this->nama_toko,
            'slug'       => $this->slug,
            'kategori'   => $this->jenis_produk,
            'kabupaten'  => $this->kabupaten,
            'status'     => $this->status,
            'lat'        => (float) $this->latitude,
            'lng'        => (float) $this->longitude,
            'alamat'     => $this->alamat,
            'jam_buka'   => $this->jam_buka,
            'foto'       => $this->foto_utama_url,
            'detail_url' => url('/peta#toko-' . $this->slug),
            'tipe'       => 'toko',  // Ikon marker berbeda dengan sanggar
        ];
    }
}
