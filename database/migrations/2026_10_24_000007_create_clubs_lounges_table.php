<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clubs_lounges', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('address', 255)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('email', 120)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('nearby_landmark', 255)->nullable();
            $table->string('ownership', 40)->nullable();
            $table->string('staff_strength', 40)->nullable();
            $table->string('best_time_to_visit', 120)->nullable();

            $table->boolean('has_live_band')->default(false);
            $table->boolean('has_casino')->default(false);
            $table->boolean('has_grills')->default(false);
            $table->boolean('has_dance_floor')->default(false);
            $table->boolean('has_indoor_bar')->default(false);
            $table->boolean('has_outdoor_bar')->default(false);
            $table->boolean('has_dj_booth')->default(false);
            $table->boolean('has_vip_section')->default(false);
            $table->boolean('has_light_sound')->default(false);
            $table->boolean('has_cctv')->default(false);
            $table->boolean('has_swimming_pool')->default(false);
            $table->boolean('has_lodging')->default(false);

            $table->string('booking_terms', 120)->nullable();
            $table->string('gate_fee_regular', 80)->nullable();
            $table->string('gate_fee_vip', 80)->nullable();
            $table->string('accommodation_fees', 80)->nullable();

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
        Schema::dropIfExists('clubs_lounges');
    }
};