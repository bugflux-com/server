<?php

namespace App\Providers;

use App\Http\ViewComposers\ErrorMetadataViewComposer;
use App\Http\ViewComposers\MasterViewComposer;
use App\Http\ViewComposers\NotificationsViewComposer;
use App\Http\ViewComposers\ProjectMetadataViewComposer;
use App\Http\ViewComposers\ReportMetadataViewComposer;
use App\Models\Error;
use App\Models\Language;
use App\Models\Project;
use App\Models\Report;
use App\Models\System;
use App\Services\FormBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Request;
use Validator;

class AppServiceProvider extends ServiceProvider
{

    static $morphMap = [
        'project' => Project::class,
        'error' => Error::class,
        'report'=> Report::class,
        'system' => System::class,
        'language' => Language::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extendImplicit('current_password', function($attribute, $value, $parameters, $validator) {
            return \Hash::check($value, \Auth::user()->password);
        });

        view()->composer('*', MasterViewComposer::class);
        view()->composer('layouts.partials.nav-notifications', NotificationsViewComposer::class);
        view()->composer('projects._menu', ProjectMetadataViewComposer::class);
        view()->composer('projects.errors._menu', ErrorMetadataViewComposer::class);
        view()->composer('projects.errors.reports._menu', ReportMetadataViewComposer::class);

        Relation::morphMap(AppServiceProvider::$morphMap);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FormBuilder::class, function($app) {
            return new FormBuilder();
        });
    }
}
