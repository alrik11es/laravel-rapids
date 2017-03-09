<?php
namespace Laravel\Rapids;

use Laravel\Rapids\Fields\CheckBoxGroup;

class Cell
{
    const TYPE_TEXT = 'text';
    const TYPE_NUMERIC = 'number';
    const TYPE_DATE = 'date';
    const TYPE_MONTH = 'selectMonth';
    const TYPE_CHECKBOXGROUP = CheckBoxGroup::class;

    public $name;
    public $field_id;
    public $type;
    public $has_error;
    public $label;
    public $star;
    public $messages;
    public $options;
    public $req;
    public $transformation;
    public $pivot;
    public $needs_order;
    public $model;

    public function transform(callable $callback)
    {
        $this->transformation = $callback;
    }

    public function pivotData(callable $callback)
    {
        $this->pivot = $callback;
    }
}