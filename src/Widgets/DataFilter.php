<?php
namespace Laravel\Rapids\Widgets;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Laravel\Rapids\Cell;
use Laravel\Rapids\Facades\Widget;
use Laravel\Rapids\Field;
use Laravel\Rapids\FormBuilder;
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

    public function __construct($query_or_filter)
    {
        $this->query = $query_or_filter;
        if($query_or_filter instanceof DataGrid){
            $this->query = $query_or_filter->getQuery();
        }

        $this->fields = collect([]);
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function add($field_id, $name, $type = Cell::TYPE_TEXT)
    {
        $field = new Cell();
        $field->name = $name;
        $field->field_id = $field_id;
        $field->type = $type;
        $field->has_error = '';
        $field->label =  $name;
        $field->star = '';
        $field->messages = '';

        $this->fields->push($field);
        $this->runFilter($field);
        return $field;
    }

    private function runFilter($field)
    {
        $field_query = Request::input($field->field_id);
        if (!empty($field_query) && $field->type == Cell::TYPE_TEXT) {
            $this->query = $this->query->where($field->field_id, 'LIKE', '%'.$field_query.'%');
        }
    }

    public function render()
    {
        $this->data['fields'] = $this->fields;

        $form = new FormBuilder();
        $form->setFields($this->fields);
        $output_form = $form->build('get', true);

        $this->data['df'] = $output_form;
        $output = \View::make('rapids::dataform_inline', $this->data)->render();
        return $output;
    }

}