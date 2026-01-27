<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;
use Livewire\WithPagination;

class RoomList extends Component
{
    use WithPagination;

    public function render()
    {
        $rooms = Room::where('published', true)->paginate(12);

        return view('livewire.room-list', [
            'rooms' => $rooms
        ])->layout('layouts.app.header');
    }
}
