<?php

namespace App\Filament\Resources\Reservations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReservationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric()
                    ->disabled(),
                TextInput::make('room_id')
                    ->required()
                    ->numeric()
                    ->disabled(),
                DatePicker::make('check_in_date')
                    ->required()
                    ->disabled(),
                DatePicker::make('check_out_date')
                    ->required()
                    ->disabled(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->disabled(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled'
                    ])
                    ->colors([
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                    ])
                    ->default('pending')
                    ->required(),
                TextInput::make('contact_name')
                    ->required()
                    ->disabled(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->disabled(),
                TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->disabled(),
            ]);
    }
}
