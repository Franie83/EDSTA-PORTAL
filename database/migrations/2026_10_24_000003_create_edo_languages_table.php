<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edo_languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('speakers', 120)->nullable();
            $table->string('area', 120)->nullable();
            $table->string('lga_list', 255)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('sort_order');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edo_languages');
    }
};