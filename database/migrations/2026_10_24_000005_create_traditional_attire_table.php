<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('traditional_attire', function (Blueprint $table) {
            $table->id();
            $table->string('ethnic_group', 80);
            $table->enum('gender', ['male', 'female', 'unisex'])->default('unisex');
            $table->text('attire_items')->nullable();
            $table->text('description')->nullable();
            $table->string('occasions', 255)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('sort_order');
            $table->index('is_active');
            $table->index('ethnic_group');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traditional_attire');
    }
};