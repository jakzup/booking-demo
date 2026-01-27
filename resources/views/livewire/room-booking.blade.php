<div class="p-6">
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Room Details -->
        <div>
            <flux:card>
                @if($room->hasImage('cover'))
                    <img src="{{ $room->image('cover') }}" alt="{{ $room->title }}" class="w-full rounded-lg -mt-6 -mx-6 mb-6">
                @endif
                
                <flux:heading size="xl" class="mb-4">{{ $room->title }}</flux:heading>
                
                <div class="prose prose-zinc dark:prose-invert max-w-none mb-6">
                    {!! $room->description !!}
                </div>
                
                <flux:separator class="my-4" />
                
                <div class="flex items-baseline gap-2">
                    <flux:heading size="xl">${{ $room->price_per_night }}</flux:heading>
                    <flux:subheading>{{ __('/ night') }}</flux:subheading>
                </div>
            </flux:card>
        </div>

        <!-- Booking Form -->
        <div>
            @if($reservationSuccess)
                <flux:card class="text-center">
                    <div class="mb-6">
                        <flux:icon.check-circle class="size-20 text-green-600 mx-auto" />
                    </div>
                    
                    <flux:heading size="xl" class="mb-2 text-green-600">
                        {{ __('Reservation Successful!') }}
                    </flux:heading>
                    
                    <flux:subheading class="mb-6">
                        {{ __('Your reservation has been submitted successfully.') }}
                    </flux:subheading>
                    
                    <div class="space-y-3">
                        <flux:button :href="route('reservations.index')" wire:navigate variant="primary" class="w-full">
                            {{ __('My Reservations') }}
                        </flux:button>
                        <flux:button :href="route('home')" wire:navigate variant="ghost" class="w-full">
                            {{ __('Back to Rooms') }}
                        </flux:button>
                    </div>
                </flux:card>
            @else
                <flux:card>
                    <flux:heading size="lg" class="mb-6">{{ __('Book This Room') }}</flux:heading>
                    
                    <!-- Step Indicator -->
                    <div class="flex items-center mb-8">
                        <div class="flex items-center flex-1">
                            <div class="size-8 rounded-full {{ $step >= 1 ? 'bg-zinc-900 dark:bg-white' : 'bg-zinc-300 dark:bg-zinc-600' }} text-white dark:text-zinc-900 flex items-center justify-center font-bold text-sm">
                                1
                            </div>
                            <div class="flex-1 h-1 {{ $step >= 2 ? 'bg-zinc-900 dark:bg-white' : 'bg-zinc-300 dark:bg-zinc-600' }} mx-2"></div>
                        </div>
                        <div class="size-8 rounded-full {{ $step >= 2 ? 'bg-zinc-900 dark:bg-white' : 'bg-zinc-300 dark:bg-zinc-600' }} text-white dark:text-zinc-900 flex items-center justify-center font-bold text-sm">
                            2
                        </div>
                    </div>

                    @if($step === 1)
                        <!-- Step 1: Dates -->
                        <form wire:submit="nextStep">
                            <flux:field>
                                <flux:label>{{ __('Check-in Date') }}</flux:label>
                                <flux:input type="date" wire:model="checkInDate" />
                                <flux:error name="checkInDate" />
                            </flux:field>

                            <flux:field>
                                <flux:label>{{ __('Check-out Date') }}</flux:label>
                                <flux:input type="date" wire:model.live="checkOutDate" />
                                <flux:error name="checkOutDate" />
                            </flux:field>

                            @if($this->totalPrice > 0)
                                <div class="bg-zinc-100 dark:bg-zinc-800 p-4 rounded-lg mb-6">
                                    <flux:heading size="lg">
                                        {{ __('Total Price') }}: ${{ number_format($this->totalPrice, 2) }}
                                    </flux:heading>
                                </div>
                            @endif

                            <flux:button type="submit" variant="primary" class="w-full">
                                {{ __('Next') }}
                            </flux:button>
                        </form>
                    @else
                        <!-- Step 2: Contact Details -->
                        <form wire:submit="book">
                            <flux:field>
                                <flux:label>{{ __('Full Name') }}</flux:label>
                                <flux:input type="text" wire:model="contactName" />
                                <flux:error name="contactName" />
                            </flux:field>

                            <flux:field>
                                <flux:label>{{ __('Email') }}</flux:label>
                                <flux:input type="email" wire:model="email" />
                                <flux:error name="email" />
                            </flux:field>

                            <flux:field>
                                <flux:label>{{ __('Phone') }}</flux:label>
                                <flux:input type="tel" wire:model="phone" />
                                <flux:error name="phone" />
                            </flux:field>

                            <div class="bg-zinc-100 dark:bg-zinc-800 p-4 rounded-lg mb-6">
                                <div class="text-sm mb-2">
                                    <p><strong>{{ __('Check-in') }}:</strong> {{ \Carbon\Carbon::parse($checkInDate)->format('d.m.Y') }}</p>
                                    <p><strong>{{ __('Check-out') }}:</strong> {{ \Carbon\Carbon::parse($checkOutDate)->format('d.m.Y') }}</p>
                                </div>
                                <flux:heading size="lg">
                                    {{ __('Total Price') }}: ${{ number_format($this->totalPrice, 2) }}
                                </flux:heading>
                            </div>

                            <div class="flex gap-3">
                                <flux:button type="button" wire:click="previousStep" variant="ghost" class="flex-1">
                                    {{ __('Back') }}
                                </flux:button>
                                <flux:button type="submit" variant="primary" class="flex-1">
                                    {{ __('Confirm Reservation') }}
                                </flux:button>
                            </div>
                        </form>
                    @endif
                </flux:card>
            @endif
        </div>
    </div>
</div>
