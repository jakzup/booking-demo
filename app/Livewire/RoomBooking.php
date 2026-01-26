<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Reservation;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RoomBooking extends Component
{
    public Room $room;
    public $checkInDate;
    public $checkOutDate;

    protected $rules = [
        'checkInDate' => 'required|date|after:today',
        'checkOutDate' => 'required|date|after:checkInDate',
    ];

    public function mount(Room $room)
    {
        $this->room = $room;
    }

    public function getTotalPriceProperty()
    {
        if ($this->checkInDate && $this->checkOutDate) {
            $checkIn = new \DateTime($this->checkInDate);
            $checkOut = new \DateTime($this->checkOutDate);
            $nights = $checkIn->diff($checkOut)->days;
            return $nights * $this->room->price_per_night;
        }
        return 0;
    }

    public function book()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate();

        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'room_id' => $this->room->id,
            'check_in_date' => $this->checkInDate,
            'check_out_date' => $this->checkOutDate,
            'number_of_guests' => $this->numberOfGuests,
            'total_price' => $this->totalPrice,
            'status' => 'pending',
            'special_requests' => $this->specialRequests,
        ]);

        session()->flash('message', 'Reservation created successfully!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.room-booking');
    }
}
