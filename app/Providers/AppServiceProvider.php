<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
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
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('phone_number', function ($attribute, $value, $parameters) {
            [$min, $max] = array_pad($parameters, 2, null);
            return preg_match("/^([0-9\s\-\+\(\)]*)$/", $value, $matches) && strlen($matches[0]) > $min ?? 1 && strlen($matches[0]) < $max ?? 20;
        });
    }
}
