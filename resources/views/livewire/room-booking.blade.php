<div class="container mx-auto px-4 py-8">
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Room Details -->
        <div>
            @if($room->hasImage('cover'))
                <img src="{{ $room->image('cover') }}" alt="{{ $room->title }}" class="w-full rounded-lg mb-4">
            @endif

            <h1 class="text-4xl font-bold mb-4">{{ $room->title }}</h1>
            
            <div class="prose max-w-none mb-6">
                {!! $room->description !!}
            </div>

            <div class="bg-gray-100 p-4 rounded-lg">
                <p class="text-2xl font-bold text-blue-600 mt-2">${{ $room->price_per_night }} per night</p>
            </div>
        </div>

        <!-- Booking Form -->
        <div>
            <div class="bg-white border rounded-lg shadow-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Book This Room</h2>

                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('message') }}
                    </div>
                @endif

                <form wire:submit="book">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Check-in Date</label>
                        <input type="date" wire:model.live="checkInDate" 
                               class="w-full border rounded px-3 py-2 @error('checkInDate') border-red-500 @enderror">
                        @error('checkInDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Check-out Date</label>
                        <input type="date" wire:model.live="checkOutDate" 
                               class="w-full border rounded px-3 py-2 @error('checkOutDate') border-red-500 @enderror">
                        @error('checkOutDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Number of Guests</label>
                        <input type="number" wire:model.live="numberOfGuests" min="1"
                               class="w-full border rounded px-3 py-2 @error('numberOfGuests') border-red-500 @enderror">
                        @error('numberOfGuests') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Special Requests (Optional)</label>
                        <textarea wire:model="specialRequests" rows="3"
                                  class="w-full border rounded px-3 py-2 @error('specialRequests') border-red-500 @enderror"></textarea>
                        @error('specialRequests') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    @if($this->totalPrice > 0)
                        <div class="bg-blue-50 p-4 rounded mb-4">
                            <p class="text-lg font-semibold">Total Price: ${{ number_format($this->totalPrice, 2) }}</p>
                        </div>
                    @endif

                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg hover:bg-blue-700 font-semibold">
                        Book Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>