<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumentasis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('tipe', ['Foto', 'Video', 'Naskah', 'Audio']);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_dokumen');
            $table->string('file_path')->nullable();        // Foto / Audio / Naskah / Video (upload)
            $table->string('thumbnail')->nullable();        // Gambar sampul (untuk Audio, Naskah, Video)
            $table->string('url_eksternal')->nullable();    // Link YouTube / sumber eksternal
            $table->enum('status', ['Aktif', 'Arsip'])->default('Aktif');
            $table->timestamps();

            $table->index(['tipe', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumentasis');
    }
};
