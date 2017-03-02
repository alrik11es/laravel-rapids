<?php
namespace Laravel\Rapids\Widgets;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Laravel\Rapids\Facades\Widget;
use Laravel\Rapids\Field;
use Laravel\Rapids\FormBuilder;
use Laravel\Rapids\WidgetManager;

class DataForm extends WidgetAbstract
{
    const FORM_CREATE = 1;
    const FORM_UPDATE = 2;

    private $data = [
        'actions' => null,
        'link' => null
    ];

    /** @var Collection */
    private $fields;
    private $post;

    public function __construct($model, $resource_route, $form_method = self::FORM_CREATE)
    {
        $this->model = $model;
        $this->fields = collect([]);
    }

    public function add($field_id, $name, $type = Field::TYPE_TEXT)
    {
        $field = new \stdClass();
        $field->name = $name;
        $field->field_id = $field_id;
        $field->type = $type;
        $field->has_error = '';
        $field->label =  $name;
        $field->star = '';
        $field->messages =  [];
        $field->req = true;
        $this->fields->push($field);
        $this->runFilter($field);
        return $this;
    }

    private function runFilter($field)
    {
        $field_query = Request::input($field->field_id);
        if (!empty($field_query) && $field->type == Field::TYPE_TEXT) {
            $this->query = $this->query->where($field->field_id, 'LIKE', '%'.$field_query.'%');
        }
    }

    public function render()
    {
        $this->data['fields'] = $this->fields;

        $form = new FormBuilder();
        $form->setFields($this->fields);
        $output_form = $form->build('post');

        $this->data['df'] = $output_form;
        $output = \View::make('rapids::dataform', $this->data)->render();
        return $output;
    }

}