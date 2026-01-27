<div class="p-6">
    <flux:heading size="xl" class="mb-6">{{ __('Available Rooms') }}</flux:heading>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
        @foreach($rooms as $room)
            <flux:card class="flex flex-col">
                @if($room->hasImage('cover'))
                    <img src="{{ $room->image('cover') }}" alt="{{ $room->title }}" class="w-full h-48 object-cover rounded-t-lg -mt-6 -mx-6 mb-4">
                @else
                    <div class="w-full h-48 bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center rounded-t-lg -mt-6 -mx-6 mb-4">
                        <flux:icon.photo class="size-12 text-zinc-400" />
                    </div>
                @endif

                <flux:heading size="lg" class="mb-2">{{ $room->title }}</flux:heading>
                
                <flux:subheading class="mb-4 line-clamp-3">
                    {{ Str::limit(strip_tags($room->description), 100) }}
                </flux:subheading>

                <flux:spacer />

                <div class="flex items-center justify-between pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:heading size="xl" class="text-zinc-900 dark:text-white">
                        ${{ $room->price_per_night }}
                        <flux:subheading class="inline">{{ __('/ night') }}</flux:subheading>
                    </flux:heading>
                </div>

                <flux:button :href="route('rooms.show', $room->slug)" wire:navigate variant="primary" class="mt-4 w-full">
                    {{ __('View Details') }}
                </flux:button>
            </flux:card>
        @endforeach
    </div>

    @if($rooms->isEmpty())
        <flux:card class="text-center py-12">
            <flux:icon.home class="size-16 text-zinc-400 mx-auto mb-4" />
            <flux:heading size="lg" class="mb-2">{{ __('No rooms available') }}</flux:heading>
            <flux:subheading>{{ __('Check back later for available rooms') }}</flux:subheading>
        </flux:card>
    @endif

    <div class="mt-8">
        {{ $rooms->links() }}
    </div>
</div>
