<?php

namespace App\Livewire;

use App\Models\Reservation;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class MyReservations extends Component
{
    public function render()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('room')
            ->orderBy('check_in_date', 'desc')
            ->get();

        return view('livewire.my-reservations', [
            'reservations' => $reservations
        ]);
    }

    public function cancelReservation($id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $reservation->update(['status' => 'cancelled']);
        
        session()->flash('message', 'Reservation cancelled successfully.');
    }
}
