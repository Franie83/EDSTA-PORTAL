<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attractions', function (Blueprint $table) {
            if (!Schema::hasColumn('attractions', 'historical_background'))   { $table->text('historical_background')->nullable()->after('description'); }
            if (!Schema::hasColumn('attractions', 'unique_selling_points'))   { $table->text('unique_selling_points')->nullable()->after('historical_background'); }
            if (!Schema::hasColumn('attractions', 'ownership'))               { $table->string('ownership', 80)->nullable()->after('unique_selling_points'); }
            if (!Schema::hasColumn('attractions', 'investment_opportunities')){ $table->text('investment_opportunities')->nullable()->after('ownership'); }
            if (!Schema::hasColumn('attractions', 'visiting_tips'))           { $table->text('visiting_tips')->nullable()->after('investment_opportunities'); }
            if (!Schema::hasColumn('attractions', 'landmarks'))               { $table->string('landmarks', 255)->nullable()->after('visiting_tips'); }
            if (!Schema::hasColumn('attractions', 'proximity_from_city'))     { $table->string('proximity_from_city', 120)->nullable()->after('landmarks'); }
        });
    }

    public function down(): void
    {
        Schema::table('attractions', function (Blueprint $table) {
            $table->dropColumn([
                'historical_background',
                'unique_selling_points',
                'ownership',
                'investment_opportunities',
                'visiting_tips',
                'landmarks',
                'proximity_from_city',
            ]);
        });
    }
};