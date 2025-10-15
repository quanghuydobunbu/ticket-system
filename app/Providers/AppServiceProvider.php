<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Repositories\BookingRepository;
use App\Repositories\BookingRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\EventRepository;
use App\Repositories\EventRepositoryInterface;
use App\Repositories\HomeRepository;
use App\Repositories\HomeRepositoryInterface;
use App\Repositories\PermissionRepository;
use App\Repositories\PermissionRepositoryInterface;
use App\Repositories\TicketRepository;
use App\Repositories\TicketRepositoryInterface;
use App\Repositories\TicketTypeRepository;
use App\Repositories\TicketTypeRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\Repositories\VenueRepository;
use App\Repositories\VenueRepositoryInterface;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
        
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(VenueRepositoryInterface::class, VenueRepository::class);
        $this->app->bind(TicketTypeRepositoryInterface::class, TicketTypeRepository::class);
        $this->app->bind(HomeRepositoryInterface::class, HomeRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class, PermissionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        Carbon::setLocale('vi');

        Gate::before(function (User $user, string $ability) {
            if (HasPermission($user, $ability)) {
                return true;
            }
            return null;
        });

        // Gate::define('users.index', function (User $user) {
        //     return HasPermission($user, 'users.index');
        // });

        // Gate::define('events.index', function (User $user) {
        //     return HasPermission($user, 'events.index');
        // });

        // Gate::define('tickets.index', function (User $user) {
        //     return HasPermission($user, 'tickets.index');
        // });

        // Gate::define('ticket_types.index', function (User $user) {
        //     return HasPermission($user, 'ticket_types.index');
        // });

        // Gate::define('bookings.index', function (User $user) {
        //     return HasPermission($user, 'bookings.index');
        // });

        // Gate::define('venues.index', function (User $user) {
        //     return HasPermission($user, 'venues.index');
        // });

        // Gate::define('categories.index', function (User $user) {
        //     return HasPermission($user, 'categories.index');
        // });
    }
}
