<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone_number');
            }
        });

        // Izinkan role customer & password nullable untuk pengguna Google OAuth
        try {
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` VARCHAR(30) NOT NULL DEFAULT 'customer';");
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `password` VARCHAR(255) NULL;");
            }
        } catch (\Throwable $e) {
            // Fallback aman jika berjalan pada SQLite in-memory test
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'google_id')) {
                $table->dropColumn('google_id');
            }
            if (Schema::hasColumn('users', 'avatar')) {
                $table->dropColumn('avatar');
            }
        });

        try {
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` ENUM('admin', 'petugas', 'user') NOT NULL DEFAULT 'user';");
            }
        } catch (\Throwable $e) {
            // Fallback aman
        }
    }
};
