<?php

namespace Motor\Backend\Providers;

use Acacha\AdminLTETemplateLaravel\Facades\AdminLTE;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Motor\Backend\Console\Commands\MotorCreatePermissionsCommand;

class MotorServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->config();
        $this->routes();
        $this->routeModelBindings();
        $this->translations();
        $this->views();
        $this->navigationItems();
        $this->permissions();
        $this->registerCommands();
        $this->migrations();
    }


    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../resources/config/motor-backend.php', 'motor-backend');
    }


    public function migrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../resources/migrations');
    }


    public function permissions()
    {
        $config = $this->app['config']->get('motor-permissions', []);
        $this->app['config']->set('motor-permissions',
            array_replace_recursive(require __DIR__ . '/../../resources/config/motor-permissions.php', $config));
    }


    public function routes()
    {
        if ( ! $this->app->routesAreCached()) {
            require __DIR__ . '/../../routes/web.php';
        }
    }


    public function config()
    {
        $this->publishes([
            __DIR__ . '/../../resources/config/motor-backend.php' => config_path('motor-backend.php'),
        ]);
    }


    public function translations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'motor-backend');

        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/motor-backend'),
        ]);
    }


    public function views()
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'motor-backend');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/motor-backend'),
        ]);
    }


    public function routeModelBindings()
    {
        Route::bind('user', function ($id) {
            return config('motor-backend.models.user')::findOrFail($id);
        });

        Route::bind('role', function ($id) {
            return config('motor-backend.models.role')::findOrFail($id);
        });

        Route::bind('permission', function ($id) {
            return config('motor-backend.models.permission')::findOrFail($id);
        });

        Route::bind('language', function ($id) {
            return config('motor-backend.models.language')::findOrFail($id);
        });

        Route::bind('client', function ($id) {
            return config('motor-backend.models.client')::findOrFail($id);
        });

        Route::bind('email_template', function ($id) {
            return config('motor-backend.models.email_template')::findOrFail($id);
        });
    }


    public function navigationItems()
    {
        $config = $this->app['config']->get('motor-navigation', []);
        $this->app['config']->set('motor-navigation',
            array_replace_recursive(require __DIR__ . '/../../resources/config/backend/navigation.php', $config));
    }


    public function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MotorCreatePermissionsCommand::class,
            ]);
        }
    }
}
