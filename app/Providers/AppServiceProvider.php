<?php

namespace App\Providers;

use App\Mixins\StrMixin;
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
        // Macro to use auto generate number module wise
        // \Illuminate\Support\Str::mixin(new StrMixin);
    }
}
