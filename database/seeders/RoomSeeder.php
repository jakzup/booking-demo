<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 rooms with translations and slugs
        Room::factory()
            ->count(10)
            ->withTranslations()
            ->create();
            
        $this->command->info('Created 10 rooms with EN and SL translations!');
    }
}

