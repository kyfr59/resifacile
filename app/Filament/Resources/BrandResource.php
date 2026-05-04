<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use App\Models\Template;
use App\Enums\PageStatus;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationLabel = 'Marques';

    public static function form(Form $form): Form
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
                                    ->required()
                                    ->reactive()
                                    ->afterStateHydrated(function ($state, callable $set) {
                                        $set('word_count', str_word_count(strip_tags($state ?? '')));
                                    })
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('word_count', str_word_count(strip_tags($state ?? '')));
                                    })
                                    ->helperText(fn ($get) => ($get('word_count') ?? 0) . ' mots'),
                                Select::make('status')
                                    ->options(\App\Enums\PageStatus::class)
                                    ->default(\App\Enums\PageStatus::DRAFT)
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
                                Select::make('template_id')
                                    ->required()
                                    ->searchable()
                                    ->label('Modèle de lettre')
                                    ->getSearchResultsUsing(fn(string $search): array => Template::where('name', 'like', "%{$search}%")->pluck('name', 'id')->toArray())
                                    ->getOptionLabelUsing(fn ($value): ?string => Template::find($value)?->name),
                            ]),
                        Tabs\Tab::make('Adresse')
                            ->schema([
                                TextInput::make('address.compagny')
                                    ->label('Raison Social')
                                    ->autofocus()
                                    ->required(),
                                TextInput::make('address.address_line_4')
                                    ->label('Adresse')
                                    ->autofocus()
                                    ->required(),
                                TextInput::make('address.address_line_2')
                                    ->label('Service ou identité du destinataire')
                                    ->autofocus(),
                                TextInput::make('address.address_line_3')
                                    ->label('Entrée, bâtiment, immeuble, résidence ou ZI')
                                    ->autofocus(),
                                TextInput::make('address.address_line_5')
                                    ->label('Boite postale, mention légale ou commune géographique')
                                    ->autofocus(),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Statut')
                    ->color(fn ($state): string => match ($state instanceof \App\Enums\PageStatus ? $state->value : $state) {
                        'DRAFT'     => 'gray',
                        'VISIBLE'   => 'success',
                        default     => 'gray',
                    }),
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
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
        ->bulkActions([
                Tables\Actions\BulkAction::make('publish')
                    ->label('Publier la sélection')
                    ->icon('heroicon-o-check')
                    ->action(fn ($records) => $records->each->update(['status' => 'VISIBLE']))
                    ->requiresConfirmation(),

                Tables\Actions\BulkAction::make('draft')
                    ->label('Mettre en brouillon')
                    ->icon('heroicon-o-pencil')
                    ->action(fn ($records) => $records->each->update(['status' => 'DRAFT'])),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
