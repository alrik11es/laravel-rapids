<?php
namespace Laravel\Rapids;

use Laravel\Rapids\Widgets\WidgetInterface;

class WidgetManager
{
    private $widget;

    public function load(WidgetInterface $widget)
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