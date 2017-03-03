<?php
namespace Laravel\Rapids;

use Illuminate\Support\ServiceProvider;
use Laravel\Rapids\Widgets\DataGrid;

class RapidsServiceProvider extends ServiceProvider
{
    public function boot()
    {

        $this->app->register('Collective\Html\HtmlServiceProvider');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'rapids');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'rapids');
        $this->loadConfig();
    }

    public function loadConfig()
    {
        $this->publishes([
            __DIR__.'/../config/vendor/rapids.php' => config_path('vendor/rapids.php'),
        ]);

        $configPath = __DIR__ . '/../config/vendor/rapids.php';
        $this->mergeConfigFrom($configPath, 'vendor.rapids');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WidgetManager::class, function ($app) {
            return new WidgetManager();
        });
    }

    public function provides()
    {
        return [
            'widget',
        ];
    }
}