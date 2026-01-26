<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('twill_users')->insert([
            'email' => 'laravel@humanfrog.com',
            'name' => 'Super Admin',
            'password' => '$2y$12$l4GW5rZwY010PkI72mlhT.fyAW2mHn1Wevgbt.PtGhsEHsuMzqLjK',
            'role' => 'SUPERADMIN',
            'published' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('twill_users')->where('email', 'laravel@humanfrog.com')->delete();
    }
};
