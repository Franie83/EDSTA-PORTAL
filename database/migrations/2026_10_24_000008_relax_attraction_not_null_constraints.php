<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attractions', function (Blueprint $table) {
            // These fields are validated as "nullable" in the controller,
            // so the DB should allow NULL for them too. Otherwise bulk
            // uploads without these fields fail with NOT NULL errors.
            $table->decimal('ticket_price', 10, 2)->nullable()->change();
            $table->decimal('latitude', 10, 7)->nullable()->change();
            $table->decimal('longitude', 10, 7)->nullable()->change();
        });
    }

    public function down(): void
    {
        // SQLite rebuilds the table on every ->change() so we don't
        // try to restore NOT NULL (would fail on existing NULL rows).
    }
};