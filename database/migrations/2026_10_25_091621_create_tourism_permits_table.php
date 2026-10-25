<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('tourism_permits', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique();
            $table->string('business_name');
            $table->string('business_type'); // e.g. Hotel, Restaurant
            $table->string('owner_name')->nullable();
            $table->string('address')->nullable();
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('status')->default('active'); // active, expired, revoked
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tourism_permits'); }
};