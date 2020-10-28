<?php

namespace App\Providers;

use App\Client;
use App\Discount;
use App\Fare;
use App\Observers\ClientObserver;
use App\Observers\DiscountObserver;
use App\Observers\FareObserver;
use App\Observers\OrderObserver;
use App\Observers\OrderReturnObserver;
use App\Observers\PaymentAttemptObserver;
use App\Observers\SavitarAccountObserver;
use App\Observers\SavitarUserObserver;
use App\Observers\SectorObserver;
use App\Observers\SessionObserver;
use App\Observers\ShowObserver;
use App\Observers\ShowTemplateObserver;
use App\Observers\TicketObserver;
use App\Observers\UserObserver;
use App\Order;
use App\OrderReturn;
use App\PaymentAttempt;
use App\Sector;
use App\Session;
use App\Show;
use App\ShowTemplate;
use App\Ticket;
use App\User;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Savitar\Auth\SavitarAccount;
use Savitar\Auth\SavitarUser;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(IdeHelperServiceProvider::class);
        } else {
            // $this->app->register(PaperTrailServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Carbon::setLocale(config('app.locale'));

        Sector::observe(SectorObserver::class);
        ShowTemplate::observe(ShowTemplateObserver::class);
        Show::observe(ShowObserver::class);
        Session::observe(SessionObserver::class);
        Fare::observe(FareObserver::class);
        Client::observe(ClientObserver::class);
        Order::observe(OrderObserver::class);
        SavitarAccount::observe(SavitarAccountObserver::class);
        SavitarUser::observe(SavitarUserObserver::class);
        User::observe(UserObserver::class);
        Discount::observe(DiscountObserver::class);
        Ticket::observe(TicketObserver::class);
        OrderReturn::observe(OrderReturnObserver::class);
        PaymentAttempt::observe(PaymentAttemptObserver::class);

        /*
        if ($this->app->environment() !== 'production') {
            DB::listen(static function ($query) {
                Log::info($query->sql);
                // Log::info($query->bindings);
                Log::info($query->time);
            });
        }
        */
    }
}
