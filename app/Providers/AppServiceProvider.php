<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use App\Observers\AuthorObserver;
use App\Observers\BookObserver;
use App\Observers\GenreObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Book::observe(BookObserver::class);
        Author::observe(AuthorObserver::class);
        Genre::observe(GenreObserver::class);
        SpatieMediaLibraryFileUpload::configureUsing(function (SpatieMediaLibraryFileUpload $component) {
            $component->getUploadedFileUsing(static function (SpatieMediaLibraryFileUpload $component, ?string $file): ?array {
                if (!$file) {
                    return null;
                }
                if (!$component->getRecord()) {
                    return null;
                }

                /** @var ?\Spatie\MediaLibrary\MediaCollections\Models\Media $media */
                $media = $component->getRecord()->getRelationValue('media')->firstWhere('uuid', $file);

                $url = null;

                if ($component->getVisibility() === 'private') {
                    $conversion = $component->getConversion();

                    try {
                        $url = $media?->getTemporaryUrl(
                            now()->addMinutes(5),
                            (filled($conversion) && $media->hasGeneratedConversion($conversion)) ? $conversion : '',
                        );
                    } catch (\Throwable $exception) {
                        //
                    }
                }

                if ($component->getConversion() && $media?->hasGeneratedConversion($component->getConversion())) {
                    $url ??= $media->getUrl($component->getConversion());
                }

                $url ??= $media?->getUrl();

                return [
                    'name' => $media?->getAttributeValue('name') ?? $media?->getAttributeValue('file_name'),
                    'size' => $media?->getAttributeValue('size'),
                    'type' => $media?->getAttributeValue('mime_type'),
                    'url' => $url,
                ];
            });
        });

        \App\Models\Book::observe(\App\Observers\BookObserver::class);
        \App\Models\Author::observe(\App\Observers\AuthorObserver::class);
        \App\Models\Genre::observe(\App\Observers\GenreObserver::class);
    }
}
