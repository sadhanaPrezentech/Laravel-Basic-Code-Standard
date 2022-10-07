<?php

namespace App\Providers;

use App\Repositories\AwsS3\AwsS3Interface;
use App\Repositories\AwsS3Repository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AwsS3Interface::class, AwsS3Repository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
