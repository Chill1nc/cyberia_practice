<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationLabel(): string
    {
        return __('messages.logs.title');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.logs.title');
    }

    public static function getModelLabel(): string
    {
        return __('messages.logs.title');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('action')
                    ->label(__('messages.logs.action')),
                Forms\Components\TextInput::make('loggable_type')
                    ->label(__('messages.logs.loggable_type'))
                    ->afterStateHydrated(fn ($state, $set) => $set('loggable_type', class_basename($state ?? ''))),
                Forms\Components\TextInput::make('loggable_id')
                    ->label(__('messages.logs.loggable_id')),
                Forms\Components\TextInput::make('causer_type')
                    ->label(__('messages.logs.causer_type'))
                    ->afterStateHydrated(fn ($state, $set) => $set('causer_type', class_basename($state ?? __('messages.logs.system')))),
                Forms\Components\TextInput::make('causer_id')
                    ->label(__('messages.logs.causer_id')),
                Forms\Components\DateTimePicker::make('created_at')
                    ->label(__('messages.logs.created_at')),
                Forms\Components\Textarea::make('payload')
                    ->label(__('messages.logs.payload'))
                    ->afterStateHydrated(fn ($state, $set) => $set('payload', is_array($state) ? json_encode($state, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : $state))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('action')
                    ->label(__('messages.logs.action'))
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'created'    => 'success',
                        'updated'    => 'warning',
                        'deleted'    => 'danger',
                        'login'      => 'info',
                        'registered' => 'primary',
                        default      => 'gray',
                    }),
                TextColumn::make('loggable_type')
                    ->label(__('messages.logs.loggable_type'))
                    ->formatStateUsing(fn ($state) => class_basename($state ?? '')),
                TextColumn::make('loggable_id')
                    ->label(__('messages.logs.loggable_id')),
                TextColumn::make('causer_type')
                    ->label(__('messages.logs.causer_type'))
                    ->formatStateUsing(fn ($state) => class_basename($state ?? __('messages.logs.system'))),
                TextColumn::make('causer_id')
                    ->label(__('messages.logs.causer_id')),
                TextColumn::make('created_at')
                    ->label(__('messages.logs.created_at'))
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
