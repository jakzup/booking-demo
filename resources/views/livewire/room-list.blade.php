<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold mb-8">Available Rooms</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($rooms as $room)
            <div class="border rounded-lg shadow-lg overflow-hidden">
                @if($room->hasImage('cover'))
                    <img src="{{ $room->image('cover') }}" alt="{{ $room->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No image</span>
                    </div>
                @endif

                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-2">{{ $room->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit(strip_tags($room->description), 100) }}</p>

                    <div class="flex justify-between items-center mb-4">
                        <span class="text-2xl font-bold text-blue-600">${{ $room->price_per_night }}</span>
                    </div>

                    <a href="{{ route('rooms.show', $room->slug) }}" 
                       class="block w-full text-center bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
                        View Details
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>