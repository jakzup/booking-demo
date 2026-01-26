# Room Reservation System with Twill CMS - Setup Instructions

This guide will walk you through adding a room reservation system to your Laravel + Livewire application with Twill CMS for admin management.

## Overview

- **Rooms Table**: Stores available rooms with details (name, description, capacity, price, etc.)
- **Reservations Table**: Tracks user reservations for specific rooms with dates
- **Frontend**: Livewire components for users to browse rooms and make reservations
- **Admin Panel**: Twill CMS at `/cms` for managing rooms

## Prerequisites

- Laravel 11 with Livewire installed ✓
- Authentication system (Fortify) installed ✓
- Database configured

---

## Step 1: Install Twill CMS

### 1.1 Install Twill via Composer

```bash
composer require area17/twill:"^3.0"
```

### 1.2 Install Twill

```bash
php artisan twill:install
```

This will:
- Publish Twill configuration files
- Create Twill migrations
- Set up the basic structure

### 1.3 Run Migrations

```bash
php artisan migrate
```

### 1.4 Create Twill Super Admin

```bash
php artisan twill:superadmin
```

Follow the prompts to create your admin user (email, name, password).

### 1.5 Configure Twill Route

The Twill CMS should now be accessible at `/cms`. You can verify this in `config/twill.php`:

```php
'admin_app_path' => 'cms',
```

---

## Step 2: Create Database Tables

### 2.1 Create Rooms Migration

```bash
php artisan make:migration create_rooms_table
```

Edit `database/migrations/XXXX_XX_XX_XXXXXX_create_rooms_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->boolean('published')->default(false);
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->integer('capacity')->default(1);
            $table->decimal('price_per_night', 10, 2)->default(0);
            $table->boolean('is_available')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
```

### 2.2 Create Reservations Migration

```bash
php artisan make:migration create_reservations_table
```

Edit `database/migrations/XXXX_XX_XX_XXXXXX_create_reservations_table.php`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->integer('number_of_guests')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
```

### 2.3 Run Migrations

```bash
php artisan migrate
```

---

## Step 3: Create Models

### 3.1 Create Room Model for Twill

```bash
php artisan twill:make:module Room
```

This creates:
- `app/Models/Room.php`
- `app/Http/Controllers/Twill/RoomController.php`
- `app/Repositories/RoomRepository.php`
- `resources/views/twill/rooms/form.blade.php`

### 3.2 Update Room Model

Edit `app/Models/Room.php`:

```php
<?php

namespace App\Models;

use A17\Twill\Models\Behaviors\HasSlug;
use A17\Twill\Models\Behaviors\HasMedias;
use A17\Twill\Models\Behaviors\HasRevisions;
use A17\Twill\Models\Model;

class Room extends Model
{
    use HasSlug, HasMedias, HasRevisions;

    protected $fillable = [
        'published',
        'name',
        'description',
        'capacity',
        'price_per_night',
        'is_available',
    ];

    protected $casts = [
        'published' => 'boolean',
        'is_available' => 'boolean',
        'price_per_night' => 'decimal:2',
    ];

    public $slugAttributes = [
        'name',
    ];

    public $mediasParams = [
        'cover' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 16 / 9,
                ],
            ],
        ],
        'gallery' => [
            'default' => [
                [
                    'name' => 'default',
                    'ratio' => 16 / 9,
                ],
            ],
        ],
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
```

### 3.3 Create Reservation Model

```bash
php artisan make:model Reservation
```

Edit `app/Models/Reservation.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'number_of_guests',
        'total_price',
        'status',
        'special_requests',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function getNightsAttribute()
    {
        return $this->check_in_date->diffInDays($this->check_out_date);
    }
}
```

### 3.4 Update User Model

Edit `app/Models/User.php` and add the relationship:

```php
public function reservations()
{
    return $this->hasMany(Reservation::class);
}
```

---

## Step 4: Configure Twill Module

### 4.1 Update Room Repository

Edit `app/Repositories/RoomRepository.php`:

```php
<?php

namespace App\Repositories;

use A17\Twill\Repositories\Behaviors\HandleSlugs;
use A17\Twill\Repositories\Behaviors\HandleMedias;
use A17\Twill\Repositories\Behaviors\HandleRevisions;
use A17\Twill\Repositories\ModuleRepository;
use App\Models\Room;

class RoomRepository extends ModuleRepository
{
    use HandleSlugs, HandleMedias, HandleRevisions;

    public function __construct(Room $model)
    {
        $this->model = $model;
    }
}
```

### 4.2 Update Room Controller

Edit `app/Http/Controllers/Twill/RoomController.php`:

```php
<?php

namespace App\Http\Controllers\Twill;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;
use A17\Twill\Models\Contracts\TwillModelContract;
use A17\Twill\Services\Forms\Fields\Input;
use A17\Twill\Services\Forms\Fields\Wysiwyg;
use A17\Twill\Services\Forms\Fields\Checkbox;
use A17\Twill\Services\Forms\Fields\Medias;
use A17\Twill\Services\Forms\Form;
use A17\Twill\Services\Listings\Columns\Text;
use A17\Twill\Services\Listings\TableColumns;

class RoomController extends BaseModuleController
{
    protected $moduleName = 'rooms';

    protected function setUpController(): void
    {
        $this->setModuleName('rooms');
        $this->setPermalinkBase('rooms');
    }

    public function getForm(TwillModelContract $model): Form
    {
        $form = parent::getForm($model);

        $form->add(
            Input::make()->name('name')->label('Room Name')->required()
        );

        $form->add(
            Input::make()->name('price_per_night')->label('Price per Night')->type('number')->step('0.01')->required()
        );

        $form->add(
            Input::make()->name('capacity')->label('Capacity (persons)')->type('number')->min(1)->required()
        );

        $form->add(
            Wysiwyg::make()->name('description')->label('Description')
        );

        $form->add(
            Checkbox::make()->name('is_available')->label('Available for Booking')
        );

        $form->add(
            Medias::make()->name('cover')->label('Cover Image')->max(1)
        );

        $form->add(
            Medias::make()->name('gallery')->label('Gallery Images')->max(10)
        );

        return $form;
    }

    protected function additionalIndexTableColumns(): TableColumns
    {
        $table = parent::additionalIndexTableColumns();

        $table->add(
            Text::make()->field('price_per_night')->title('Price/Night')
        );

        $table->add(
            Text::make()->field('capacity')->title('Capacity')
        );

        return $table;
    }
}
```

### 4.3 Register Twill Module

Edit `config/twill-navigation.php` (create if it doesn't exist):

```php
<?php

return [
    'rooms' => [
        'title' => 'Rooms',
        'module' => true,
    ],
];
```

Or register in `routes/twill.php`:

// TODO: meybi tole ne bo vredu?

```php
<?php

use A17\Twill\Facades\TwillNavigation;
use A17\Twill\View\Components\Navigation\NavigationLink;

TwillNavigation::addLink(
    NavigationLink::make()->forModule('rooms')->title('Rooms')
);
```

---

## Step 5: Create Frontend Livewire Components

### 5.1 Create Room Listing Component

```bash
php artisan make:livewire RoomList
```

Edit `app/Livewire/RoomList.php`:

```php
<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;

class RoomList extends Component
{
    public function render()
    {
        $rooms = Room::where('published', true)
            ->where('is_available', true)
            ->get();

        return view('livewire.room-list', [
            'rooms' => $rooms
        ]);
    }
}
```

Edit `resources/views/livewire/room-list.blade.php`:

```blade
<div class="container mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold mb-8">Available Rooms</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($rooms as $room)
            <div class="border rounded-lg shadow-lg overflow-hidden">
                @if($room->hasImage('cover'))
                    <img src="{{ $room->image('cover') }}" alt="{{ $room->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No image</span>
                    </div>
                @endif

                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-2">{{ $room->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ Str::limit($room->description, 100) }}</p>

                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-700">Capacity: {{ $room->capacity }} person(s)</span>
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
```

### 5.2 Create Room Details and Booking Component

```bash
php artisan make:livewire RoomBooking
```

Edit `app/Livewire/RoomBooking.php`:

```php
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
    public $numberOfGuests = 1;
    public $specialRequests;

    protected $rules = [
        'checkInDate' => 'required|date|after:today',
        'checkOutDate' => 'required|date|after:checkInDate',
        'numberOfGuests' => 'required|integer|min:1',
        'specialRequests' => 'nullable|string|max:500',
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

        if ($this->numberOfGuests > $this->room->capacity) {
            $this->addError('numberOfGuests', 'Number of guests exceeds room capacity.');
            return;
        }

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
        return redirect()->route('reservations.show', $reservation);
    }

    public function render()
    {
        return view('livewire.room-booking');
    }
}
```

Edit `resources/views/livewire/room-booking.blade.php`:

```blade
<div class="container mx-auto px-4 py-8">
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Room Details -->
        <div>
            @if($room->hasImage('cover'))
                <img src="{{ $room->image('cover') }}" alt="{{ $room->name }}" class="w-full rounded-lg mb-4">
            @endif

            <h1 class="text-4xl font-bold mb-4">{{ $room->name }}</h1>
            
            <div class="prose max-w-none mb-6">
                {!! $room->description !!}
            </div>

            <div class="bg-gray-100 p-4 rounded-lg">
                <p class="text-lg"><strong>Capacity:</strong> {{ $room->capacity }} person(s)</p>
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
                        <input type="number" wire:model.live="numberOfGuests" min="1" max="{{ $room->capacity }}"
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
```

---

## Step 6: Create Routes

Edit `routes/web.php`:

```php
<?php

use App\Livewire\RoomList;
use App\Livewire\RoomBooking;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Room routes
Route::get('/rooms', RoomList::class)->name('rooms.index');
Route::get('/rooms/{room:slug}', RoomBooking::class)->name('rooms.show');

// Reservation routes (add these later)
Route::middleware(['auth'])->group(function () {
    Route::get('/my-reservations', function () {
        // Create a component for this
    })->name('reservations.index');
});
```

---

## Step 7: Access the Admin Panel

1. Start your development server:
   ```bash
   php artisan serve
   ```

2. Navigate to `http://localhost:8000/cms`

3. Login with the super admin credentials you created

4. Go to "Rooms" in the sidebar

5. Create some rooms with images, descriptions, pricing, etc.

---

## Step 8: Test the Frontend

1. Navigate to `http://localhost:8000/rooms` to see the room listing

2. Click on a room to view details and the booking form

3. Try making a reservation (must be logged in)

---

## Optional Enhancements

### Add Availability Checking

Update `RoomBooking.php` to check if the room is available for the selected dates:

```php
public function checkAvailability()
{
    $overlapping = Reservation::where('room_id', $this->room->id)
        ->where('status', '!=', 'cancelled')
        ->where(function ($query) {
            $query->whereBetween('check_in_date', [$this->checkInDate, $this->checkOutDate])
                  ->orWhereBetween('check_out_date', [$this->checkInDate, $this->checkOutDate])
                  ->orWhere(function ($q) {
                      $q->where('check_in_date', '<=', $this->checkInDate)
                        ->where('check_out_date', '>=', $this->checkOutDate);
                  });
        })
        ->exists();

    return !$overlapping;
}
```

Call this in your `book()` method before creating the reservation.

### Create User Reservations Page

```bash
php artisan make:livewire MyReservations
```

Display user's reservations with ability to cancel.

### Add Email Notifications

Create notification when a reservation is made:

```bash
php artisan make:notification ReservationCreated
```

---

## Troubleshooting

**Issue: Twill images not showing**
- Make sure you've run `php artisan storage:link`
- Check file permissions on `storage/` directory

**Issue: 404 on /cms**
- Clear config cache: `php artisan config:clear`
- Clear route cache: `php artisan route:clear`

**Issue: Twill module not showing**
- Make sure migrations ran successfully
- Check `routes/twill.php` or `config/twill-navigation.php`

---

## Summary

You now have:
- ✅ Twill CMS at `/cms` for managing rooms
- ✅ Rooms and Reservations database tables
- ✅ Frontend for browsing rooms and making reservations
- ✅ Livewire components for interactive booking
- ✅ User authentication integration

Next steps would be to add payment processing, email notifications, and admin views for managing reservations!
