<?php

namespace App\Providers;

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
        $this->app->singleton('EventsRepository', \App\EventsRepository::class);
        $this->app->singleton('UsersRepository', \App\UsersRepository::class);
        $this->app->bind(\App\Mailing\Mailing::class, \App\Mailing\BasicMailer::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
