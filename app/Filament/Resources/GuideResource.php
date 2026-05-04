<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuideResource\Pages;
use App\Filament\Resources\GuideResource\RelationManagers;
use App\Models\Guide;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GuideResource extends Resource
{
    protected static ?string $model = Guide::class;

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationLabel = 'Guides';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Général')
                    ->schema([
                        TextInput::make('title')
                            ->label('Titre')
                            ->autofocus()
                            ->required(),
                        FileUpload::make('visual')
                            ->label('Image de couverture')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('guides')
                            ->visibility('public'),
                        Select::make('status')
                            ->options([
                                'draft'     => 'Brouillon',
                                'published' => 'Publié',
                            ])
                            ->default('draft')
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
                        Fieldset::make('SEO')
                            ->schema([
                                TextInput::make('seo_title')
                                    ->label('Titre')
                                    ->autofocus()
                                    ->required(),
                                Textarea::make('seo_description')
                                    ->label('Description')
                                    ->autofocus()
                                    ->required()
                            ])->columns(1),
                    ]),
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
                    ->formatStateUsing(function ($state) {
                    return $state instanceof PageStatus
                        ? $state->value
                        : $state;
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'draft'     => 'gray',
                        'published' => 'success',
                        default     => 'gray',
                    }),
                Tables\Columns\TextColumn::make('title')
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
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'draft'     => 'Brouillon',
                        'published' => 'Publié',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('publish')
                    ->label('Publier la sélection')
                    ->icon('heroicon-o-check')
                    ->action(fn ($records) => $records->each->update(['status' => 'published']))
                    ->requiresConfirmation(),

                Tables\Actions\BulkAction::make('draft')
                    ->label('Mettre en brouillon')
                    ->icon('heroicon-o-pencil')
                    ->action(fn ($records) => $records->each->update(['status' => 'draft'])),
            ]);
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
            'index' => Pages\ListGuides::route('/'),
            'create' => Pages\CreateGuide::route('/create'),
            'edit' => Pages\EditGuide::route('/{record}/edit'),
        ];
    }
}
