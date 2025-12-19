<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Enums\OrderStatus;
use App\Enums\PostageType;
use App\Helpers\Accounting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $title = 'Commandes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('number'),
                Tables\Columns\TextColumn::make('amount')
                    ->formatStateUsing(fn (string $state): string => Accounting::priceFormat($state)),
                Tables\Columns\TextColumn::make('postage')
                    ->badge()
                    ->color(fn (PostageType $state): string => match ($state) {
                        PostageType::ECONOMIC_LETTER, PostageType::GREEN_LETTER => 'success',
                        PostageType::REGISTERED_LETTER => 'warning',
                        PostageType::FOLLOWED_LETTER => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (OrderStatus $state): string => match ($state) {
                        OrderStatus::PAID => 'success',
                        OrderStatus::UNPAID => 'danger',
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
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
