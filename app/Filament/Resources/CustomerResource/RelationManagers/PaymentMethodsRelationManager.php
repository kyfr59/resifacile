<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Enums\PaymentMethodStatus;
use App\Helpers\Accounting;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PaymentMethodsRelationManager extends RelationManager
{
    protected static string $relationship = 'paymentMethods';

    protected static ?string $title = 'Moyens de paiement';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('data')
                    ->label('Carte')
                    ->formatStateUsing(fn (object $state): string => $state->brand .' - '. $state->pan . ' - ' . $state->card_expiry_month . '/' . $state->card_expiry_year . ' - ' . Str::of( $state->card_holder)->lower()->title()),
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (PaymentMethodStatus $state): string => match ($state) {
                        PaymentMethodStatus::DISABLED => 'gray',
                        PaymentMethodStatus::ENABLED => 'success',
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Mis à jour le')
                    ->dateTime('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
