<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Reservation;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RoomBooking extends Component
{
    public Room $room;
    public $step = 1;
    public $checkInDate;
    public $checkOutDate;
    public $contactName;
    public $email;
    public $phone;
    public $reservationSuccess = false;

    protected $rules = [
        'checkInDate' => 'required|date|after:today',
        'checkOutDate' => 'required|date|after:checkInDate',
        'contactName' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
    ];

    public function mount(Room $room)
    {
        $this->room = $room;
        $this->email = Auth::user()->email;
        $this->contactName = Auth::user()->name;
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

    public function nextStep()
    {
        $this->validate([
            'checkInDate' => 'required|date|after:today',
            'checkOutDate' => 'required|date|after:checkInDate',
        ]);

        $this->step = 2;
    }

    public function previousStep()
    {
        $this->step = 1;
    }

    public function book()
    {
        $this->validate();

        Reservation::create([
            'user_id' => Auth::id(),
            'room_id' => $this->room->id,
            'check_in_date' => $this->checkInDate,
            'check_out_date' => $this->checkOutDate,
            'total_price' => $this->totalPrice,
            'status' => 'pending',
            'contact_name' => $this->contactName,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);

        $this->reservationSuccess = true;
    }

    public function render()
    {
        return view('livewire.room-booking');
    }
}
