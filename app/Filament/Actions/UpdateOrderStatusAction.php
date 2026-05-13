<?php

namespace App\Filament\Actions;

use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class UpdateOrderStatusAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'update_status';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Update Status')
            ->icon(Heroicon::OutlinedArrowPath)
            ->color('warning')
            ->modalHeading('Update Order Status')
            ->modalWidth('sm')
            ->form([
                Select::make('status')
                    ->label('New status')
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default(fn (Order $record) => $record->status),
            ])
            ->action(function (Order $record, array $data): void {
                $old = $record->status;
                $record->update(['status' => $data['status']]);

                Notification::make()
                    ->title('Status updated')
                    ->body("Order #{$record->id} changed from {$old} → {$data['status']}.")
                    ->success()
                    ->send();
            });
    }
}
