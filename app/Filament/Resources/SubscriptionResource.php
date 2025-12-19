<?php

namespace App\Filament\Resources;

use App\Actions\Subscription\UnsubscribedProcessAction;
use App\Enums\SubscriptionStatus;
use App\Filament\Resources\SubscriptionResource\Pages;
use App\Helpers\Accounting;
use App\Models\Subscription;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'Abonnements';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Tables\Grouping\Group::make('status')
                    ->getTitleFromRecordUsing(fn (Subscription $record): string => ucfirst($record->status->value))
                    ->collapsible(true),
                Tables\Grouping\Group::make('created_at')
                    ->date()
                    ->collapsible(true),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('designation'),
                Tables\Columns\TextColumn::make('customer')
                    ->label('Client')
                    ->formatStateUsing(fn (object $state): string => '<a href="' . route('filament.admin.resources.customers.view', ['record' => $state->id]) . '">'.$state->name.'</a>')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('customer', function ($query) use ($search) {
                                return $query
                                    ->where('first_name', 'ilike', "%{$search}%")
                                    ->orWhere('last_name', 'ilike', "%{$search}%")
                                    ->orWhere('email', 'ilike', "%{$search}%");
                            });
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('customer.email')
                    ->label('Email'),
                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->formatStateUsing(fn (string $state): string => Accounting::priceFormat($state)),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (SubscriptionStatus $state): string => match ($state) {
                        SubscriptionStatus::TRIAL => 'gray',
                        SubscriptionStatus::CANCELED, SubscriptionStatus::CANCEL_REQUEST => 'danger',
                        SubscriptionStatus::RECURRING => 'success',
                        SubscriptionStatus::LATE_PAYMENT => 'warning',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('résilier')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->modalIcon('heroicon-o-exclamation-triangle')
                    ->modalIconColor('warning')
                    ->modalHeading('Résilier l\'abonnement')
                    ->modalDescription('Êtes-vous sûr de vouloir résilier cet abonnement ?')
                    ->modalSubmitActionLabel('Oui, résilier')
                    ->action(function (Subscription $subscription) {
                        (new UnsubscribedProcessAction($subscription))->process();
                    })
                    ->visible(fn (Subscription $subscription) => $subscription->status === SubscriptionStatus::RECURRING)
                    ->icon('heroicon-m-exclamation-triangle'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscriptions::route('/'),
        ];
    }
}
