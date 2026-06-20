<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('author_id')
                    ->relationship('author', 'last_name')
                    ->required(),
                Select::make('genre_id')
                    ->relationship('genre', 'name')
                    ->required(),
                TextInput::make('title')->required(),
                TextInput::make('price')->numeric()->required(),
                TextInput::make('year')->numeric()->required(),
                TextInput::make('publisher'),
                TextInput::make('pages')->numeric(),
                TextInput::make('size'),
                TextInput::make('cover_type'),
                TextInput::make('weight')->numeric(),
                TextInput::make('age_limit'),
                SpatieMediaLibraryFileUpload::make('images')
                    ->collection('images')
                    ->multiple()
                    ->image(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('images')
                    ->collection('images')
                    ->label('Обложка')
                    ->circular(),
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.last_name')
                    ->label('Автор')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('genre.name')
                    ->label('Жанр')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Цена')
                    ->sortable(),
                TextColumn::make('year')
                    ->label('Год')
                    ->sortable(),
                TextColumn::make('reviews_avg_rating')
                    ->avg('reviews', 'rating')
                    ->label('Средняя оценка')
                    ->sortable()
                    ->numeric(1),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
