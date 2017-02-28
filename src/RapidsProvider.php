<?php
namespace Laravel\Rapids;

class RapidsProvider
{
    public function boot()
    {
        $this->loadConfig();

    }

    public function loadConfig()
    {
        $this->publishes([
            __DIR__.'/../../config/vendor/rapids.php' => config_path('vendor/rapids.php'),
        ]);

        $configPath = __DIR__ . '/../../config/vendor/rapids.php';
        $this->mergeConfigFrom($configPath, 'vendor.rapids');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}