<?php
namespace Laravel\Rapids;

use Laravel\Rapids\Widgets\WidgetAbstract;

class WidgetManager
{
    private $widget;

    public function load(WidgetAbstract $widget)
    {
        $this->widget = $widget;
        return $this;
    }

    private function render() : string
    {
        return (string) $this->widget->render();
    }

    public function __toString()
    {
        return $this->render();
    }

}