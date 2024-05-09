<?php

namespace App\Providers;

use App\Models\MatterRequest;
use App\Models\MatterSubType;
use App\Models\MatterType;
use App\Models\User;
use App\Policies\MatterRequestPolicy;
use App\Policies\MatterSubTypePolicy;
use App\Policies\MatterTypePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(MatterType::class, MatterTypePolicy::class);
        Gate::policy(MatterSubType::class, MatterSubTypePolicy::class);
        Gate::policy(MatterRequest::class, MatterRequestPolicy::class);
    }
}
