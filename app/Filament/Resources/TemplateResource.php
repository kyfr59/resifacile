<?php

namespace App\Filament\Resources;

use App\DataTransferObjects\TemplateData;
use App\Filament\Resources\TemplateResource\Pages\CreateTemplate;
use App\Filament\Resources\TemplateResource\Pages\EditTemplate;
use App\Filament\Resources\TemplateResource\Pages\ListTemplates;
use App\Models\Template;
use Creagia\FilamentCodeField\CodeField;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static ?string $navigationGroup = 'CMS';

    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected static ?string $navigationLabel = 'Modèles';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Général')
                            ->schema([
                                TextInput::make('name')
                                    ->autofocus()
                                    ->required(),
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
                                    ->afterStateHydrated(function (CodeField $component, array $state) {
                                        $component->state(json_encode($state, JSON_PRETTY_PRINT));
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
