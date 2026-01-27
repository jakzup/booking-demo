<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_filament_admin')->default(false)->after('email');
        });

        // insert filament superadmin
        DB::table('users')->insert([
            'email' => 'laravel@humanfrog.com',
            'name' => 'Superadmin',
            'password' => '$2y$12$l4GW5rZwY010PkI72mlhT.fyAW2mHn1Wevgbt.PtGhsEHsuMzqLjK',
            'is_filament_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_filament_admin');
        });
    }
};
