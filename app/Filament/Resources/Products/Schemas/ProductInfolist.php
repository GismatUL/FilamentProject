<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Details')
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('description')
                            ->placeholder('—'),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('price')
                                    ->money('USD'),
                                TextEntry::make('stock'),
                            ]),
                    ]),
            ]);
    }
}
