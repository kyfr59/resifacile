<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Enums\TransactionStatus;
use App\Helpers\Accounting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'transactions';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('transaction_id')
                    ->label('Transaction ID'),
                TextColumn::make('amount')
                    ->label('Montant')
                    ->formatStateUsing(fn (string $state): string => Accounting::priceFormat($state)),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (TransactionStatus $state): string => match ($state) {
                        TransactionStatus::CAPTURED, TransactionStatus::SUCCEEDED => 'success',
                        TransactionStatus::BLOCKED, TransactionStatus::AUTHENTICATION_FAILED, TransactionStatus::DENIED, TransactionStatus::REFUSED => 'warning',
                        TransactionStatus::CHARGED_BACK => 'error',
                        default => 'gray'
                    }),
                TextColumn::make('error_type')
                    ->label('Type d\'erreur'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
