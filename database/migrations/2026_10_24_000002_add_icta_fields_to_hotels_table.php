<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            // --- Contact ---
            if (!Schema::hasColumn('hotels', 'phone'))            { $table->string('phone', 40)->nullable()->after('address'); }
            if (!Schema::hasColumn('hotels', 'email'))            { $table->string('email', 120)->nullable()->after('phone'); }
            if (!Schema::hasColumn('hotels', 'website'))          { $table->string('website', 255)->nullable()->after('email'); }
            if (!Schema::hasColumn('hotels', 'nearby_landmark'))  { $table->string('nearby_landmark', 255)->nullable()->after('website'); }

            // --- Basic classification ---
            if (!Schema::hasColumn('hotels', 'star_rating'))      { $table->unsignedTinyInteger('star_rating')->nullable()->after('nearby_landmark'); }
            if (!Schema::hasColumn('hotels', 'ownership'))        { $table->string('ownership', 40)->nullable()->after('star_rating'); }
            if (!Schema::hasColumn('hotels', 'staff_strength'))   { $table->string('staff_strength', 40)->nullable()->after('ownership'); }

            // --- Rooms ---
            if (!Schema::hasColumn('hotels', 'rooms_total'))      { $table->unsignedInteger('rooms_total')->nullable()->after('staff_strength'); }
            if (!Schema::hasColumn('hotels', 'rooms_functional')) { $table->unsignedInteger('rooms_functional')->nullable()->after('rooms_total'); }
            if (!Schema::hasColumn('hotels', 'rooms_suites'))     { $table->unsignedInteger('rooms_suites')->nullable()->after('rooms_functional'); }

            // --- Facilities (booleans) ---
            if (!Schema::hasColumn('hotels', 'has_swimming_pool'))     { $table->boolean('has_swimming_pool')->default(false)->after('rooms_suites'); }
            if (!Schema::hasColumn('hotels', 'has_gym'))               { $table->boolean('has_gym')->default(false)->after('has_swimming_pool'); }
            if (!Schema::hasColumn('hotels', 'has_spa'))               { $table->boolean('has_spa')->default(false)->after('has_gym'); }
            if (!Schema::hasColumn('hotels', 'has_wifi'))              { $table->boolean('has_wifi')->default(false)->after('has_spa'); }
            if (!Schema::hasColumn('hotels', 'has_cctv'))              { $table->boolean('has_cctv')->default(false)->after('has_wifi'); }
            if (!Schema::hasColumn('hotels', 'has_event_hall'))        { $table->boolean('has_event_hall')->default(false)->after('has_cctv'); }
            if (!Schema::hasColumn('hotels', 'has_live_band'))         { $table->boolean('has_live_band')->default(false)->after('has_event_hall'); }
            if (!Schema::hasColumn('hotels', 'has_parking'))           { $table->boolean('has_parking')->default(false)->after('has_live_band'); }
            if (!Schema::hasColumn('hotels', 'has_concierge'))         { $table->boolean('has_concierge')->default(false)->after('has_parking'); }
            if (!Schema::hasColumn('hotels', 'has_laundry'))           { $table->boolean('has_laundry')->default(false)->after('has_concierge'); }
            if (!Schema::hasColumn('hotels', 'has_first_aid'))         { $table->boolean('has_first_aid')->default(false)->after('has_laundry'); }
            if (!Schema::hasColumn('hotels', 'has_emergency_exit'))    { $table->boolean('has_emergency_exit')->default(false)->after('has_first_aid'); }
            if (!Schema::hasColumn('hotels', 'has_fire_extinguisher')) { $table->boolean('has_fire_extinguisher')->default(false)->after('has_emergency_exit'); }

            // --- Pricing / services ---
            if (!Schema::hasColumn('hotels', 'price_range_feeding'))  { $table->string('price_range_feeding', 80)->nullable()->after('has_fire_extinguisher'); }
            if (!Schema::hasColumn('hotels', 'recreation_offerings')) { $table->text('recreation_offerings')->nullable()->after('price_range_feeding'); }
            if (!Schema::hasColumn('hotels', 'booking_terms'))        { $table->string('booking_terms', 120)->nullable()->after('recreation_offerings'); }
        });
    }

    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'email', 'website', 'nearby_landmark',
                'star_rating', 'ownership', 'staff_strength',
                'rooms_total', 'rooms_functional', 'rooms_suites',
                'has_swimming_pool', 'has_gym', 'has_spa', 'has_wifi', 'has_cctv',
                'has_event_hall', 'has_live_band', 'has_parking', 'has_concierge',
                'has_laundry', 'has_first_aid', 'has_emergency_exit', 'has_fire_extinguisher',
                'price_range_feeding', 'recreation_offerings', 'booking_terms',
            ]);
        });
    }
};