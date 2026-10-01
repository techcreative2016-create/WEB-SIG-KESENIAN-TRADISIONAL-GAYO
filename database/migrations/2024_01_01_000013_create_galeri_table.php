<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel galeri (Media Gallery untuk Sanggar dan Toko).
     * Menggunakan polymorphic relationship agar satu tabel dipakai bersama.
     */
    public function up(): void
    {
        Schema::create('galeri', function (Blueprint $table) {
            $table->id();

            // Polymorphic columns — galeri_able_type & galeri_able_id
            // Memungkinkan galeri milik Sanggar atau Toko sekaligus
            $table->morphs('galeri_able');                         // galeri_able_type + galeri_able_id

            $table->string('judul')->nullable();                   // Judul / caption foto
            $table->string('path_file');                           // Path file gambar/video
            $table->enum('tipe_media', ['foto', 'video'])
                  ->default('foto');                               // Tipe media
            $table->unsignedTinyInteger('urutan')->default(0);     // Urutan tampil di galeri
            $table->boolean('is_utama')->default(false);           // Tandai sebagai foto utama

            $table->timestamps();

            // Index untuk performa query galeri per entitas
            $table->index(['galeri_able_type', 'galeri_able_id', 'urutan']);
        });
    }

    /**
     * Batalkan migrasi tabel galeri.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri');
    }
};
