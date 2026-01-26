<?php

namespace App\Models\Translations;

use A17\Twill\Models\Model;
use App\Models\Room;

class RoomTranslation extends Model
{
    protected $baseModuleModel = Room::class;
    
    protected $fillable = [
        'title',
        'description',
    ];
}
