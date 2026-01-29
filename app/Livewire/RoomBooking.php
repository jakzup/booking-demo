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
        'checkInDate' => 'required|date|after_or_equal:today',
        'checkOutDate' => 'required|date|after_or_equal:checkInDate',
        'contactName' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|min:10|max:20',
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
            $days = $checkIn->diff($checkOut)->days + 1;
            return $days * $this->room->price_per_night;
        }
        return 0;
    }

    public function nextStep()
    {
        $this->validate([
            'checkInDate' => 'required|date|after_or_equal:today',
            'checkOutDate' => 'required|date|after_or_equal:checkInDate',
        ], $this->getMessages());

        // Check for conflicting reservations
        $conflict = Reservation::where('room_id', $this->room->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->whereNull('deleted_at')
            ->where(function ($query) {
                $query->whereBetween('check_in_date', [$this->checkInDate, $this->checkOutDate])
                    ->orWhereBetween('check_out_date', [$this->checkInDate, $this->checkOutDate])
                    ->orWhere(function ($q) {
                        $q->where('check_in_date', '<=', $this->checkInDate)
                            ->where('check_out_date', '>=', $this->checkOutDate);
                    });
            })
            ->exists();

        if ($conflict) {
            $this->addError('checkOutDate', __('reservations.dates_unavailable'));
            return;
        }

        $this->step = 2;
    }

    public function previousStep()
    {
        $this->step = 1;
    }

    public function book()
    {
        $this->validate($this->rules, $this->getMessages());

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

    protected function getMessages()
    {
        return [
            'checkInDate.required' => __('reservations.check_in_date_required'),
            'checkInDate.date' => __('reservations.check_in_date_invalid'),
            'checkInDate.after_or_equal' => __('reservations.check_in_date_past'),
            'checkOutDate.required' => __('reservations.check_out_date_required'),
            'checkOutDate.date' => __('reservations.check_out_date_invalid'),
            'checkOutDate.after_or_equal' => __('reservations.check_out_date_invalid_range'),
            'contactName.required' => __('reservations.contact_name_required'),
            'contactName.string' => __('reservations.contact_name_invalid'),
            'contactName.max' => __('reservations.contact_name_max'),
            'email.required' => __('reservations.email_required'),
            'email.email' => __('reservations.email_invalid'),
            'phone.required' => __('reservations.phone_required'),
            'phone.min' => __('reservations.phone_invalid'),
            'phone.max' => __('reservations.phone_invalid'),
        ];
    }

    public function render()
    {
        return view('livewire.room-booking')->layout('layouts.app.header');
    }
}
