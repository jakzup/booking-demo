<div class="container mx-auto px-4 py-8">
    <div class="grid md:grid-cols-2 gap-8">
        <div>
            @if($room->hasImage('cover'))
                <img src="{{ $room->image('cover') }}" alt="{{ $room->title }}" class="w-full rounded-lg mb-4">
            @endif
            <h1 class="text-4xl font-bold mb-4">{{ $room->title }}</h1>
            <div class="prose max-w-none mb-6">{!! $room->description !!}</div>
            <div class="bg-gray-100 p-4 rounded-lg">
                <p class="text-2xl font-bold text-blue-600">${{ $room->price_per_night }} na noč</p>
            </div>
        </div>
        <div>
            @if($reservationSuccess)
                <div class="bg-white border rounded-lg shadow-lg p-8 text-center">
                    <div class="mb-6">
                        <svg class="w-20 h-20 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-green-600 mb-4">Uspešna rezervacija!</h2>
                    <p class="text-gray-600 mb-6">Vaša rezervacija je bila uspešno oddana.</p>
                    <div class="space-y-2">
                        <a href="{{ route('reservations.index') }}" class="block w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700">Moje rezervacije</a>
                        <a href="{{ route('rooms.index') }}" class="block w-full bg-gray-200 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-300">Nazaj na sobe</a>
                    </div>
                </div>
            @else
                <div class="bg-white border rounded-lg shadow-lg p-6">
                    <h2 class="text-2xl font-bold mb-6">Rezerviraj sobo</h2>
                    <div class="flex items-center mb-8">
                        <div class="flex items-center flex-1">
                            <div class="w-8 h-8 rounded-full {{ $step >= 1 ? 'bg-blue-600' : 'bg-gray-300' }} text-white flex items-center justify-center font-bold">1</div>
                            <div class="flex-1 h-1 {{ $step >= 2 ? 'bg-blue-600' : 'bg-gray-300' }} mx-2"></div>
                        </div>
                        <div class="w-8 h-8 rounded-full {{ $step >= 2 ? 'bg-blue-600' : 'bg-gray-300' }} text-white flex items-center justify-center font-bold">2</div>
                    </div>
                    @if($step === 1)
                        <form wire:submit="nextStep">
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2 font-semibold">Datum prijave</label>
                                <input type="date" wire:model="checkInDate" class="w-full border rounded px-3 py-2 @error('checkInDate') border-red-500 @enderror">
                                @error('checkInDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 mb-2 font-semibold">Datum odjave</label>
                                <input type="date" wire:model.live="checkOutDate" class="w-full border rounded px-3 py-2 @error('checkOutDate') border-red-500 @enderror">
                                @error('checkOutDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            @if($this->totalPrice > 0)
                                <div class="bg-blue-50 p-4 rounded mb-6">
                                    <p class="text-lg font-semibold">Skupna cena: ${{ number_format($this->totalPrice, 2) }}</p>
                                </div>
                            @endif
                            <button type="submit" class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 font-semibold">Naprej</button>
                        </form>
                    @else
                        <form wire:submit="book">
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2 font-semibold">Ime in priimek</label>
                                <input type="text" wire:model="contactName" class="w-full border rounded px-3 py-2 @error('contactName') border-red-500 @enderror">
                                @error('contactName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2 font-semibold">Email</label>
                                <input type="email" wire:model="email" class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-6">
                                <label class="block text-gray-700 mb-2 font-semibold">Telefonska številka</label>
                                <input type="tel" wire:model="phone" class="w-full border rounded px-3 py-2 @error('phone') border-red-500 @enderror">
                                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div class="bg-blue-50 p-4 rounded mb-6">
                                <div class="text-sm text-gray-600 mb-2">
                                    <p><strong>Prijava:</strong> {{ \Carbon\Carbon::parse($checkInDate)->format('d.m.Y') }}</p>
                                    <p><strong>Odjava:</strong> {{ \Carbon\Carbon::parse($checkOutDate)->format('d.m.Y') }}</p>
                                </div>
                                <p class="text-lg font-semibold">Skupna cena: ${{ number_format($this->totalPrice, 2) }}</p>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" wire:click="previousStep" class="flex-1 bg-gray-200 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-300 font-semibold">Nazaj</button>
                                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 font-semibold">Potrdi rezervacijo</button>
                            </div>
                        </form>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
