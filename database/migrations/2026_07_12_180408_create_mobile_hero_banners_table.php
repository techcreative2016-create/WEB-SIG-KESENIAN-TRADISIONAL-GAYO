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
        Schema::create('mobile_hero_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle');
            $table->string('tag')->nullable();
            $table->string('image_path');
            $table->json('stats')->nullable(); // array of {icon, label}
            $table->string('section_title')->nullable();
            $table->text('description')->nullable();
            $table->json('info_rows')->nullable(); // array of {icon, label, value}
            $table->text('quote')->nullable();
            $table->string('tips_title')->nullable();
            $table->json('tips')->nullable(); // array of strings
            $table->boolean('is_active')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_hero_banners');
    }
};
