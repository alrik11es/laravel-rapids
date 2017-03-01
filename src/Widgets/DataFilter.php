<?php
namespace Laravel\Rapids\Widgets;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Laravel\Rapids\Facades\Widget;
use Laravel\Rapids\WidgetManager;

class DataFilter extends WidgetAbstract
{
    private $data = [
        'actions' => null,
        'link' => null
    ];
    /** @var Builder */
    private $query;

    /** @var Collection */
    private $fields;

    public function __construct($query)
    {
        $this->query = $query;
        $this->fields = collect([]);
    }

    public function add($field_id, $name, $needs_order = false)
    {
        $field = new \stdClass();
        $field->name = $name;
        $field->field_id = $field_id;
        $field->needs_order = $needs_order;
        $this->fields->push($field);
        return $this;
    }

    public function render()
    {
        $this->data['fields'] = $this->fields;
        $this->runOrderBys();
        $this->data['paginator'] = $this->runValueTransformations();
        $output = \View::make('rapids::grid.datafilter', $this->data)->render();
        return $output;
    }

}