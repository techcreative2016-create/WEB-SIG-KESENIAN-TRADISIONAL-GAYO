<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use App\Traits\LogsActivity;

class JadwalPenampilan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'jadwal_penampilan';

    protected $fillable = [
        'nama_sanggar',
        'nama_acara',
        'deskripsi',
        'jenis_kesenian',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi_acara',
        'alamat_acara',
        'latitude',
        'longitude',
        'status',
        'tiket_info',
        'poster',
        'user_id',
    ];

    protected $casts = [
        'tanggal'       => 'date',          // Carbon date object
        'waktu_mulai'   => 'datetime:H:i',  // Format jam
        'waktu_selesai' => 'datetime:H:i',
        'latitude'      => 'float',
        'longitude'     => 'float',
    ];

    // -------------------------------------------------------------------------
    // Accessors
    // -------------------------------------------------------------------------

    public function getPosterUrlAttribute(): string
    {
        return $this->poster
            ? asset('storage/' . $this->poster)
            : asset('images/placeholder-acara.jpg');
    }

    /**
     * Cek apakah jadwal jatuh pada hari ini.
     */
    public function getIsHariIniAttribute(): bool
    {
        return $this->tanggal->isToday();
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Jadwal hari ini — digunakan di halaman live schedule publik.
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', Carbon::today())
                     ->where('status', '!=', 'Dibatalkan')
                     ->orderBy('waktu_mulai');
    }

    /**
     * Jadwal mendatang (besok dan seterusnya).
     */
    public function scopeMendatang($query)
    {
        return $query->whereDate('tanggal', '>=', Carbon::today())
                     ->whereIn('status', ['Terjadwal', 'Berlangsung'])
                     ->orderBy('tanggal')
                     ->orderBy('waktu_mulai');
    }

    /**
     * Filter berdasarkan jenis kesenian.
     */
    public function scopeByJenisKesenian($query, string $jenis)
    {
        return $query->where('jenis_kesenian', $jenis);
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------



    /**
     * Relasi ke pembuat (Admin).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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
            'nama'        => $this->nama_acara,
            'slug'        => $this->id, // Jadwal menggunakan ID untuk routing
            'kategori'    => $this->jenis_kesenian,
            'kabupaten'   => 'Bener Meriah', // Default karena acara umumnya di Bener Meriah
            'status'      => $this->status,
            'lat'         => (float) $this->latitude,
            'lng'         => (float) $this->longitude,
            'alamat'      => $this->alamat_acara,
            'foto'        => $this->poster_url,
            'detail_url'  => route('publik.jadwal.show', $this->id),
            'tipe'        => 'pertunjukan',
        ];
    }
}
