<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasMedias;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use A17\Twill\Models\Model;

class Room extends Model 
{
    use HasTranslation, HasSlug, HasMedias, HasFactory;

    protected $fillable = [
        'published',
        'price_per_night',
    ];
    
    public $translatedAttributes = [
        'title',
        'description',
    ];

    protected $casts = [
        'published' => 'boolean',
        'price_per_night' => 'decimal:2',
    ];

    public $slugAttributes = [
        'title',
    ];

    public $mediasParams = [
        'cover' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 16 / 9,
                ],
            ],
        ],
        'gallery' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 16 / 9,
                ],
            ],
        ],
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    
    public function getRouteKeyName()
    {
        return 'id';
    }
    
    public function resolveRouteBinding($value, $field = null)
    {
        // Try to find by slug first
        $slug = \DB::table('room_slugs')
            ->where('slug', $value)
            ->where('active', true)
            ->first();
            
        if ($slug) {
            return $this->where('id', $slug->room_id)->firstOrFail();
        }
        
        // Fallback to ID
        return $this->where('id', $value)->firstOrFail();
    }
    
}
