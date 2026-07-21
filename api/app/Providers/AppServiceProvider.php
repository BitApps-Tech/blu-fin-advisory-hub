<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $uploadRoot = storage_path('app/public/uploads');
        if (!is_dir($uploadRoot)) {
            File::ensureDirectoryExists($uploadRoot);
        }

        $publicStorageLink = public_path('storage');
        if (!file_exists($publicStorageLink)) {
            try {
                app('files')->link(storage_path('app/public'), $publicStorageLink);
            } catch (\Throwable $e) {
                // Symlinks may be disabled on shared hosting; uploads still work via storage/app/public.
            }
        }
    }
}
