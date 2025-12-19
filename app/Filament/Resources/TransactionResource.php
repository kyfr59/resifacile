<?php

namespace App\Filament\Resources;

use App\Actions\Transaction\RefundProcess;
use App\Enums\SubscriptionStatus;
use App\Enums\TransactionStatus;
use App\Filament\Resources\TransactionResource\Pages;
use App\Helpers\Accounting;
use App\Models\Subscription;
use App\Models\Transaction;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationIcon = 'heroicon-o-currency-euro';

    protected static ?string $navigationLabel = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Montant')
                    ->alignEnd()
                    ->formatStateUsing(fn (string $state): string => Accounting::priceFormat($state))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (TransactionStatus $state): string => match ($state) {
                        TransactionStatus::CAPTURED, TransactionStatus::SUCCEEDED => 'success',
                        TransactionStatus::BLOCKED, TransactionStatus::AUTHENTICATION_FAILED, TransactionStatus::DENIED, TransactionStatus::REFUSED => 'warning',
                        TransactionStatus::CHARGED_BACK => 'error',
                        default => 'gray'
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('error_type')
                    ->label('Type d\'erreur')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transaction_id')
                    ->label('Type d\'erreur')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('transactionable')
                    ->label('Type de transaction')
                    ->formatStateUsing(fn (Model $state): string => $state?->number ?? 'Abo ' . Str::lower($state->designation)),
                TextColumn::make('transactionable.customer')
                    ->label('Client')
                    ->formatStateUsing(fn (object $state): string => '<a href="' . route('filament.admin.resources.customers.view', ['record' => $state->id]) . '">'.$state->name.'</a>')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query
                            ->whereHas('transactionable', function ($query) use ($search) {
                                $query->whereHas('customer', function ($query) use ($search) {
                                    return $query
                                        ->where('first_name', 'ilike', "%{$search}%")
                                        ->orWhere('last_name', 'ilike', "%{$search}%")
                                        ->orWhere('email', 'ilike', "%{$search}%");
                                });
                            });
                    })
                    ->html()
                    ->sortable(),
                TextColumn::make('transactionable.customer.email')
                    ->label('Email'),
                TextColumn::make('mid')
                    ->label('MID')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Date de mise à jour')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Date de création')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('rembourser')
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-exclamation-triangle')
                    ->modalIconColor('warning')
                    ->color('danger')
                    ->modalHeading('Rembourser la transaction')
                    ->modalDescription('Êtes-vous sûr de vouloir rembourser la transaction ?')
                    ->modalSubmitActionLabel('Oui, rembourser')
                    ->action(function (Transaction $transaction) {
                        (new RefundProcess($transaction))->send(null);
                    })
                    ->visible(fn (Transaction $transaction) => $transaction->status == TransactionStatus::CAPTURED)
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
            'index' => Pages\ListTransactions::route('/'),
        ];
    }
}
