<?php
namespace Laravel\Rapids\Facades;

use Illuminate\Support\Facades\Facade;
use Laravel\Rapids\WidgetManager;

class Widget extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return WidgetManager::class; }
}