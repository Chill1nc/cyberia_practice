<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('author_name')
                    ->label('Имя автора')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('rating')
                    ->label('Оценка')
                    ->options([
                        1 => '1 - Ужасно',
                        2 => '2 - Плохо',
                        3 => '3 - Нормально',
                        4 => '4 - Хорошо',
                        5 => '5 - Отлично',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->label('Текст отзыва')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns([
                Tables\Columns\TextColumn::make('author_name')
                    ->label('Автор')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Оценка')
                    ->sortable(),
                Tables\Columns\TextColumn::make('comment')
                    ->label('Текст отзыва')
                    ->limit(50),
            ])
            ->filters([
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
