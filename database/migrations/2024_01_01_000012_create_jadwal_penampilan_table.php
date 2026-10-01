<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel jadwal_penampilan (Jadwal Pertunjukan Seni Live).
     */
    public function up(): void
    {
        Schema::create('jadwal_penampilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanggar_id')
                  ->constrained('sanggars')
                  ->cascadeOnDelete();                              // Relasi ke sanggar penampil

            $table->string('nama_acara');                          // Nama acara/pertunjukan
            $table->text('deskripsi')->nullable();                 // Deskripsi acara
            $table->enum('jenis_kesenian', [
                'Didong',
                'Tari Guel',
                'Kerawang Gayo',
                'Teganing',
                'Campuran'
            ]);                                                    // Jenis kesenian ditampilkan
            $table->date('tanggal');                               // Tanggal penampilan
            $table->time('waktu_mulai');                           // Jam mulai
            $table->time('waktu_selesai')->nullable();             // Jam selesai
            $table->string('lokasi_acara');                        // Nama lokasi venue
            $table->string('alamat_acara')->nullable();            // Alamat detail venue

            // Koordinat khusus lokasi acara (bisa berbeda dari sanggar)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->enum('status', [
                'Terjadwal',
                'Berlangsung',
                'Selesai',
                'Dibatalkan'
            ])->default('Terjadwal');                              // Status acara
            $table->string('tiket_info')->nullable();              // Info harga tiket
            $table->string('poster')->nullable();                  // Path poster acara

            $table->timestamps();

            // Index untuk query jadwal hari ini
            $table->index(['tanggal', 'status']);
        });
    }

    /**
     * Batalkan migrasi tabel jadwal_penampilan.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_penampilan');
    }
};
