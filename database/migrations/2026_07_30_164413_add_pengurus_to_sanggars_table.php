<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sanggars', function (Blueprint $table) {
            $table->string('nama_pimpinan')->nullable()->after('status');
            $table->string('peran_pimpinan')->nullable()->after('nama_pimpinan');
            $table->string('nama_koreografer')->nullable()->after('peran_pimpinan');
            $table->string('peran_koreografer')->nullable()->after('nama_koreografer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sanggars', function (Blueprint $table) {
            $table->dropColumn(['nama_pimpinan', 'peran_pimpinan', 'nama_koreografer', 'peran_koreografer']);
        });
    }
};
