<?php
namespace Laravel\Rapids\Widgets;

class DataGrid implements WidgetInterface
{

    public function source($query)
    {

        return $this;
    }

    public function add($field, $name)
    {
        return $this;
    }

    public function __toString()
    {
        return ''; // This widget rendering
    }
}