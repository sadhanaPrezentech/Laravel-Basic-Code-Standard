<?php

namespace App\Providers;

use App\Helpers\FunctionHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Illuminate\View\ViewServiceProvider as BaseViewServiceProvider;

class ViewServiceProvider extends BaseViewServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $entity = FunctionHelper::getEntity();

        // composer to provide to datatables_actions.
        View::composer(['*.datatables_actions'], function ($view) use ($entity) {
            $url = $view->href_url ?? false;

            $view->with(['entity' => $entity] + ['href_url' => $url] + ['model' => $view->model] + ['permissionModule' => $entity['targetModel'] ?: null]);
        });

        View::composer('layouts.admin', function ($view) {
            if (Auth::user() && Auth::user()->hasRole('admin')) {
                $routes = [
                    'logoutRoute' => route('logout'),
                    'homeRoute' => route('admin.dashboard'),
                ];
                $view->with($routes);
            }
        });

        // composer for roles create/edit/show
        // View::composer(
        //     ['roles.create', 'roles.edit', 'roles.show'],
        //     'App\Http\View\Composers\RoleComposer'
        // );
    }
}
