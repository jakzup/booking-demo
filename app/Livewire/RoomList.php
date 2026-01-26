<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;

class RoomList extends Component
{
    public function render()
    {
        $rooms = Room::where('published', true)->get();

        return view('livewire.room-list', [
            'rooms' => $rooms
        ]);
    }
}
