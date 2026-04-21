<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WebhookCallResource\Pages;
use App\Models\WebhookCall;
use App\Models\Sending;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WebhookCallResource extends Resource
{
    protected static ?string $model = WebhookCall::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?string $navigationLabel = 'Evènements Maileva';

    protected static ?string $modelLabel = 'Appel Webhook';

    protected static ?string $pluralModelLabel = 'Evènements Maileva';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('name', 'maileva');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations générales')
                    ->schema([
                        Forms\Components\TextInput::make('url')
                            ->label('URL')
                            ->url()
                            ->required()
                            ->maxLength(2048)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make("Données transmises dans l'appel")
                    ->schema([
                        Forms\Components\Textarea::make('payload')
                            ->label('Payload')
                            ->rows(14)
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => is_array($state)
                                ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                : $state),

                        Forms\Components\Textarea::make('headers')
                            ->label('En-têtes')
                            ->rows(5)
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => is_array($state)
                                ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                : $state),
                    ]),

                Forms\Components\Section::make('Réponse')
                    ->schema([
                        Forms\Components\Textarea::make('response')
                            ->label('Corps de la réponse')
                            ->rows(8)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('exception')
                            ->label('Exception / Erreur')
                            ->rows(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('payload_resource_id')
                    ->label('ID Maileva')
                    ->getStateUsing(function ($record): ?string {
                        $payload = is_string($record->payload)
                            ? json_decode($record->payload, true)
                            : (array) $record->payload;

                        return $payload['resource_id'] ?? null;
                    })
                    ->copyable()
                    ->copyMessage('ID Maileva copié')
                    ->fontFamily('mono')
                    ->limit(36)
                    ->tooltip(fn ($state) => $state)
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw(
                            "payload->>'resource_id' ILIKE ?",
                            ["%{$search}%"]
                        );
                    }),

                Tables\Columns\TextColumn::make('sending_id')
                    ->label('ID Sending')
                    ->alignCenter()
                    ->getStateUsing(fn ($record) => Sending::fromMailevaId($record->payload['resource_id'])?->id ?? '-')
                    ->url(fn ($record) =>
                        ($sending = Sending::fromMailevaId($record->payload['resource_id']))
                            ? route('filament.admin.resources.sendings.view', ['record' => $sending->id])
                            : null
                    ),

                Tables\Columns\TextColumn::make('payload_event_type')
                    ->label('Événement')
                    ->getStateUsing(function ($record): ?string {
                        $payload = is_string($record->payload)
                            ? json_decode($record->payload, true)
                            : (array) $record->payload;

                        return $payload['event_type'] ?? null;
                    })
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        str_contains((string) $state, 'PROCESSED') => 'success',
                        str_contains((string) $state, 'ERROR')     => 'danger',
                        str_contains((string) $state, 'PENDING')   => 'warning',
                        default                                     => 'gray',
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw(
                            "payload->>'event_type' ILIKE ?",
                            ["%{$search}%"]
                        );
                    }),

                Tables\Columns\IconColumn::make('exception')
                    ->label('Etat')
                    ->boolean()
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->getStateUsing(fn ($record): bool => ! empty($record->exception)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date d\'appel')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_type')
                    ->label('Statut')
                    ->options([
                        'ON_STATUS_ACCEPTED'   => 'ON_STATUS_ACCEPTED',
                        'ON_STATUS_PROCESSED'  => 'ON_STATUS_PROCESSED',
                        'ON_STATUS_PENDING'    => 'ON_STATUS_PENDING',
                        'ON_STATUS_ERROR'      => 'ON_STATUS_ERROR',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return $query->whereRaw(
                            "payload->>'event_type' = ?",
                            [$data['value']]
                        );
                    }),

                Tables\Filters\Filter::make('resource_id')
                    ->form([
                        Forms\Components\TextInput::make('resource_id')
                            ->label('Resource ID')
                            ->placeholder('UUID…'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['resource_id'])) {
                            return $query;
                        }

                        return $query->whereRaw(
                            "payload->>'resource_id' = ?",
                            [$data['resource_id']]
                        );
                    }),

                Tables\Filters\SelectFilter::make('response_status_code')
                    ->label('Code HTTP')
                    ->options([
                        '2xx' => 'Succès (2xx)',
                        '4xx' => 'Erreur client (4xx)',
                        '5xx' => 'Erreur serveur (5xx)',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return match ($data['value']) {
                            '2xx' => $query->whereBetween('response_status_code', [200, 299]),
                            '4xx' => $query->whereBetween('response_status_code', [400, 499]),
                            '5xx' => $query->whereBetween('response_status_code', [500, 599]),
                            default => $query,
                        };
                    }),

                Tables\Filters\Filter::make('has_exception')
                    ->label('Avec erreur')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('exception')->where('exception', '!=', '')),

                Tables\Filters\Filter::make('no_exception')
                    ->label('Sans erreur')
                    ->query(fn (Builder $query): Builder => $query->whereNull('exception')->orWhere('exception', '')),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Du'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Au'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWebhookCalls::route('/'),
            'view'   => Pages\ViewWebhookCall::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Les webhook calls sont créés automatiquement
    }
}