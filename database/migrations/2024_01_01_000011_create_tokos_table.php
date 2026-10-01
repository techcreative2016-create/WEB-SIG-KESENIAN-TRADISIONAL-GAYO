<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel tokos (Toko Kesenian Tradisional Gayo).
     */
    public function up(): void
    {
        Schema::create('tokos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko');                            // Nama toko kesenian
            $table->string('slug')->unique();                       // URL-friendly identifier
            $table->enum('jenis_produk', [
                'Kerawang Gayo',
                'Alat Musik',
                'Pakaian Adat',
                'Kerajinan Tangan',
                'Campuran'
            ]);                                                     // Jenis produk dijual
            $table->text('deskripsi')->nullable();                  // Deskripsi toko
            $table->string('alamat');                               // Alamat lengkap
            $table->enum('kabupaten', [
                'Bener Meriah'
            ]);                                                     // Kabupaten lokasi
            $table->string('kecamatan')->nullable();                // Kecamatan

            // Koordinat GPS — DECIMAL(10,7) presisi tinggi
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->string('no_telepon', 20)->nullable();           // Nomor kontak
            $table->string('jam_buka', 50)->nullable();             // Contoh: "08:00 - 17:00"
            $table->enum('status', ['Buka', 'Tutup'])->default('Buka'); // Status operasional
            $table->string('foto_utama')->nullable();               // Path foto utama

            $table->timestamps();
            $table->softDeletes();

            // Index untuk filter peta
            $table->index(['jenis_produk', 'kabupaten', 'status']);
        });
    }

    /**
     * Batalkan migrasi tabel tokos.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokos');
    }
};
