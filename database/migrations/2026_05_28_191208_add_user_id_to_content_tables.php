<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Temukan master admin pertama untuk dijadikan fallback data lama
        $firstAdmin = DB::table('users')->where('role', 'master')->first();
        $fallbackId = $firstAdmin ? $firstAdmin->id : null;

        $tables = ['sanggars', 'tokos', 'jadwal_penampilan', 'beritas'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $tableSchema) {
                $tableSchema->unsignedBigInteger('user_id')->nullable()->after('id');
                $tableSchema->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });

            // Set data lama menjadi milik fallbackId (Master)
            if ($fallbackId) {
                DB::table($table)->update(['user_id' => $fallbackId]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['sanggars', 'tokos', 'jadwal_penampilan', 'beritas'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $tableSchema) {
                $tableSchema->dropForeign(['user_id']);
                $tableSchema->dropColumn('user_id');
            });
        }
    }
};
