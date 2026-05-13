<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('customer.name')
                                    ->label('Customer'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'new' => 'info',
                                        'processing' => 'warning',
                                        'shipped' => 'primary',
                                        'delivered' => 'success',
                                        'cancelled' => 'danger',
                                        default => 'gray',
                                    }),
                                TextEntry::make('notes')
                                    ->placeholder('—')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Order Items')
                    ->schema([
                        RepeatableEntry::make('orderItems')
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label('Product'),
                                TextEntry::make('qty')
                                    ->label('Quantity'),
                                TextEntry::make('unit_price')
                                    ->label('Unit Price')
                                    ->money('USD'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }
}
