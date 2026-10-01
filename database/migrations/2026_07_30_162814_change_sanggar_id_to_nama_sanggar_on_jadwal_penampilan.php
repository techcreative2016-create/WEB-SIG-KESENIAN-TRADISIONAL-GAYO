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
        Schema::table('jadwal_penampilan', function (Blueprint $table) {
            $table->string('nama_sanggar')->nullable()->after('sanggar_id');
        });

        // Copy existing data
        \Illuminate\Support\Facades\DB::table('jadwal_penampilan')
            ->join('sanggars', 'jadwal_penampilan.sanggar_id', '=', 'sanggars.id')
            ->update(['jadwal_penampilan.nama_sanggar' => \Illuminate\Support\Facades\DB::raw('sanggars.nama_sanggar')]);

        Schema::table('jadwal_penampilan', function (Blueprint $table) {
            // Drop foreign key if it exists
            $table->dropForeign(['sanggar_id']);
            $table->dropColumn('sanggar_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_penampilan', function (Blueprint $table) {
            $table->foreignId('sanggar_id')->nullable()->constrained('sanggars')->onDelete('cascade');
        });

        // We cannot reliably copy back, but we can try if there's an exact match
        
        Schema::table('jadwal_penampilan', function (Blueprint $table) {
            $table->dropColumn('nama_sanggar');
        });
    }
};
