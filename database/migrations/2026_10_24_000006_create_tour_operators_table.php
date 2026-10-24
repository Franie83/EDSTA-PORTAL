<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_operators', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('address', 255)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('nearby_landmark', 255)->nullable();
            $table->string('ownership', 40)->nullable();
            $table->string('staff_strength', 40)->nullable();

            $table->string('scope_of_service', 80)->nullable();
            $table->string('mode_of_transport', 255)->nullable();
            $table->string('service_availability', 120)->nullable();
            $table->string('age_limit', 80)->nullable();

            $table->string('cost_range_edo_south', 80)->nullable();
            $table->string('cost_range_edo_north', 80)->nullable();
            $table->string('cost_range_edo_central', 80)->nullable();

            $table->boolean('aware_of_edsta')->default(false);
            $table->text('suggestions')->nullable();
            $table->unsignedInteger('yearly_interstate_visitors')->nullable();
            $table->unsignedInteger('yearly_international_visitors')->nullable();

            $table->string('image_url', 500)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_operators');
    }
};