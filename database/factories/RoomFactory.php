<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'published' => true,
            'price_per_night' => fake()->numberBetween(50, 300),
        ];
    }
    
    /**
     * Create room with translations
     */
    public function withTranslations(): static
    {
        return $this->afterCreating(function ($room) {
            // Create English translation
            \DB::table('room_translations')->insert([
                'room_id' => $room->id,
                'locale' => 'en',
                'title' => fake()->words(3, true),
                'description' => '<p>' . fake()->paragraph() . '</p>',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create Slovenian translation
            \DB::table('room_translations')->insert([
                'room_id' => $room->id,
                'locale' => 'sl',
                'title' => fake()->words(3, true),
                'description' => '<p>' . fake()->paragraph() . '</p>',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create slugs for both languages
            $enTitle = \DB::table('room_translations')
                ->where('room_id', $room->id)
                ->where('locale', 'en')
                ->value('title');
                
            $slTitle = \DB::table('room_translations')
                ->where('room_id', $room->id)
                ->where('locale', 'sl')
                ->value('title');
            
            \DB::table('room_slugs')->insert([
                'room_id' => $room->id,
                'locale' => 'en',
                'slug' => Str::slug($enTitle),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            \DB::table('room_slugs')->insert([
                'room_id' => $room->id,
                'locale' => 'sl',
                'slug' => Str::slug($slTitle),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }
}

