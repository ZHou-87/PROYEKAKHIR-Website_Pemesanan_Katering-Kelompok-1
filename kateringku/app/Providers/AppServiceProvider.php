<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        Model::preventLazyLoading(! app()->isProduction());
        Model::preventSilentlyDiscardingAttributes(! app()->isProduction());

        Paginator::useBootstrapFive();

        Gate::define('kelola-menu', fn (User $user) => $user->hasAnyRole(['owner', 'admin']));
        Gate::define('validasi-pesanan', fn (User $user) => $user->hasAnyRole(['owner', 'admin']));
        Gate::define('kelola-user', fn (User $user) => $user->hasRole('owner'));
        Gate::define('lihat-laporan', fn (User $user) => $user->hasRole('owner'));

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
