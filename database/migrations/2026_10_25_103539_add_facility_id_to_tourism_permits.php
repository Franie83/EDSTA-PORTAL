<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('tourism_permits', function (Blueprint $table) {
            $table->unsignedBigInteger('facility_id')->nullable();
            $table->string('facility_type')->nullable();
        });
    }
    public function down(): void {
        Schema::table('tourism_permits', function (Blueprint $table) {
            $table->dropColumn(['facility_id', 'facility_type']);
        });
    }
};