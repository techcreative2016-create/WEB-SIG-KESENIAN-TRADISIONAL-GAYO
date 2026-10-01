<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel sanggars (Sanggar Seni Tradisional Gayo).
     */
    public function up(): void
    {
        Schema::create('sanggars', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sanggar');                          // Nama sanggar seni
            $table->string('slug')->unique();                       // URL-friendly identifier
            $table->enum('kategori', ['Tari', 'Musik', 'Kriya']);   // Kategori kesenian
            $table->text('deskripsi')->nullable();                  // Deskripsi sanggar
            $table->string('alamat');                               // Alamat lengkap
            $table->enum('kabupaten', [
                'Bener Meriah'
            ]);                                                     // Kabupaten lokasi
            $table->string('kecamatan')->nullable();                // Kecamatan

            // Koordinat GPS — DECIMAL(10,7) cukup untuk presisi ~1cm
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->string('no_telepon', 20)->nullable();           // Nomor kontak
            $table->string('email')->nullable();                    // Email sanggar
            $table->string('instagram')->nullable();                // Username Instagram
            $table->enum('status', ['Aktif', 'Vakum'])->default('Aktif'); // Status kegiatan
            $table->string('foto_utama')->nullable();               // Path foto utama

            $table->timestamps();
            $table->softDeletes();                                  // Soft delete untuk audit

            // Index untuk query map filter
            $table->index(['kategori', 'kabupaten', 'status']);
        });
    }

    /**
     * Batalkan migrasi tabel sanggars.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanggars');
    }
};
