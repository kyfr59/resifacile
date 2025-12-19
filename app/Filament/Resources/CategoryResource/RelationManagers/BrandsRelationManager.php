<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandsRelationManager extends RelationManager
{
    protected static string $relationship = 'brands';

    protected static ?string $title = 'Marques';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Général')
                            ->schema([
                                TextInput::make('name')
                                    ->autofocus()
                                    ->required(),
                                MarkdownEditor::make('article')
                                    ->autofocus()
                                    ->required(),
                                Fieldset::make('SEO')
                                    ->schema([
                                        TextInput::make('seo_title')
                                            ->autofocus()
                                            ->required(),
                                        Textarea::make('seo_description')
                                            ->autofocus()
                                            ->required(),
                                    ])->columns(1),
                            ]),
                        Tabs\Tab::make('Adresse')
                            ->schema([
                                TextInput::make('address.company')
                                    ->autofocus()
                                    ->required(),
                                TextInput::make('address.address_line_2')
                                    ->autofocus()
                                    ->required(),
                                TextInput::make('address.address_line_3')
                                    ->autofocus()
                                    ->required(),
                                TextInput::make('address.address_line_4')
                                    ->autofocus()
                                    ->required(),
                                TextInput::make('address.address_line_5')
                                    ->autofocus()
                                    ->required(),
                                Grid::make()
                                    ->schema([
                                        TextInput::make('address.postal_code')
                                            ->autofocus()
                                            ->required(),
                                        TextInput::make('address.city')
                                            ->autofocus()
                                            ->required(),
                                    ])->columns(2),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
