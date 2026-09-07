<?php

namespace App\Filament\Resources;


use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use App\DataTransferObjects\TemplateData;
use App\Filament\Resources\TemplateResource\Pages\CreateTemplate;
use App\Filament\Resources\TemplateResource\Pages\EditTemplate;
use App\Filament\Resources\TemplateResource\Pages\ListTemplates;
use App\Models\Template;
use Creagia\FilamentCodeField\CodeField;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static string|\UnitEnum|null $navigationGroup = 'CMS';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationLabel = 'Modèles';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Général')
                        ->schema([
                            TextInput::make('name')
                                ->autofocus()
                                ->required(),

                            Select::make('categories')
                                ->label('Catégories')
                                ->relationship('categories', 'name')
                                ->multiple()
                                ->searchable()
                                ->preload(),

                            MarkdownEditor::make('article')
                                ->autofocus()
                                ->required(),

                            Section::make('SEO')
                                ->schema([
                                    TextInput::make('seo_title')
                                        ->autofocus()
                                        ->required(),

                                    Textarea::make('seo_description')
                                        ->autofocus()
                                        ->required(),
                                ]),
                        ]),
                        Tab::make('Model')
                            ->schema([
                                Toggle::make('model.is_new_type')
                                    ->required(),

                                TextInput::make('object')
                                    ->autofocus()
                                    ->required(),

                                Textarea::make('model.model')
                                    ->autosize()
                                    ->required(),

                                CodeField::make('model.group_fields')
                                    ->setLanguage(CodeField::JSON)
                                    ->withLineNumbers()
                                    ->afterStateHydrated(function (CodeField $component, ?array $state) {
                                        $component->state(json_encode($state ?? [],  JSON_PRETTY_PRINT));
                                     })
                                    ->dehydrateStateUsing(fn (string $state): array => json_decode($state, true))
                                    ->required(),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('categories.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y')
                    ->searchable()
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
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
            'index' => ListTemplates::route('/'),
            'create' => CreateTemplate::route('/create'),
            'edit' => EditTemplate::route('/{record}/edit'),
        ];
    }
}
