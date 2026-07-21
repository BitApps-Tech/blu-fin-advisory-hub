<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Models\MenuCategory::class => \App\Policies\MenuCategoryPolicy::class,
        \App\Models\MenuItem::class => \App\Policies\MenuItemPolicy::class,
        \App\Models\Specialty::class => \App\Policies\SpecialtyPolicy::class,
        \App\Models\GalleryItem::class => \App\Policies\GalleryItemPolicy::class,
        \App\Models\ContactMessage::class => \App\Policies\ContactMessagePolicy::class,
        \App\Models\Order::class => \App\Policies\OrderPolicy::class,
        \App\Models\Setting::class => \App\Policies\SettingPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
