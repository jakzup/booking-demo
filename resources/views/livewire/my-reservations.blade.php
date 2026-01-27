<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold mb-8">Moje rezervacije</h2>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if($reservations->isEmpty())
        <div class="bg-gray-100 p-8 text-center rounded-lg">
            <p class="text-gray-600">Trenutno nimate nobenih rezervacij.</p>
            <a href="{{ route('home') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Prebrskaj sobe
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reservations as $reservation)
                <div class="border rounded-lg p-6 {{ $reservation->status === 'cancelled' ? 'bg-gray-50' : 'bg-white' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold mb-2">{{ $reservation->room->title }}</h3>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-gray-600 text-sm">Prijava</p>
                                    <p class="font-semibold">{{ $reservation->check_in_date->format('d.m.Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Odjava</p>
                                    <p class="font-semibold">{{ $reservation->check_out_date->format('d.m.Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Kontakt ime</p>
                                    <p class="font-semibold">{{ $reservation->contact_name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Email</p>
                                    <p class="font-semibold">{{ $reservation->email }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Telefon</p>
                                    <p class="font-semibold">{{ $reservation->phone }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600 text-sm">Cena</p>
                                    <p class="font-semibold">${{ $reservation->total_price }}</p>
                                </div>
                            </div>

                            <div>
                                <span class="inline-block px-3 py-1 rounded text-sm font-semibold
                                    @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </div>
                        </div>

                        @if($reservation->status !== 'cancelled' && $reservation->check_in_date->isFuture())
                            <button wire:click="cancelReservation({{ $reservation->id }})" 
                                    wire:confirm="Ali ste prepričani, da želite preklicati to rezervacijo?"
                                    class="ml-4 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                Prekliči
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
