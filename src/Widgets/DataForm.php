<?php
namespace Laravel\Rapids\Widgets;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Laravel\Rapids\Cell;
use Laravel\Rapids\Facades\Widget;
use Laravel\Rapids\FormBuilder;
use Laravel\Rapids\WidgetManager;
use Illuminate\Database\Eloquent\Model;

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
    /** @var Model */
    private $model;
    private $route;
    private $request;
    private $form_method;

    public function __construct($model, $resource_route = null)
    {
        $this->route = $resource_route;
        $this->model = $model;
        $this->fields = collect([]);
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function add($field_id, $name = null, $type = Cell::TYPE_TEXT, $options = [])
    {
        $field = new Cell();
        $field->name = $name;
        $field->field_id = $field_id;
        $field->type = $type;
        $field->has_error = '';
        if(isset($this->model->$field_id)) {
            $field->value = $this->model->$field_id;
        } else {
            $field->value = '';
        }
        $field->label =  $name;
        $field->star = '';
        $field->messages =  [];
        $field->options = $options;
        $field->req = true;
        $this->fields->push($field);
        return $field;
    }

    public function request($field_id, $type = Cell::TYPE_TEXT, $options = [])
    {
        $field = new Cell();
        $field->field_id = $field_id;
        $field->type = $type;
        $field->has_error = '';

        if(isset($options['format'])){
            $field->format = $options['format'];
        }

        $field->messages =  [];
        $field->req = true;
        $this->fields->push($field);
        return $field;
    }

    public function render()
    {
        $this->data['fields'] = $this->fields;

        $form = new FormBuilder();

        if(isset($this->model->id)){
            $form->setActionUrl($this->route.'/'.$this->model->id);
            $form->setMethod('patch');
        } else {
            $form->setActionUrl($this->route);
            $form->setMethod('post');
        }

        $form->setCells($this->fields);
        $output_form = $form->build();

        $this->data['df'] = $output_form;
        $output = \View::make('rapids::dataform', $this->data)->render();
        return $output;
    }

    public function operate()
    {
        foreach($this->fields as $field) {
            $field_id = $field->field_id;
            if(isset($field->callback) && is_callable($field->callback)){
                $field_value = ($field->transformation)($this->model, Request::input($field_id));
            } else {
                if ($field->type == Field::TYPE_DATE) {
                    $field_value = Carbon::createFromFormat($field->format, Request::input($field_id));
                } else {
                    $field_value = Request::input($field_id);
                }
            }
            $this->model->$field_id = $field_value;
        }
        $this->model->save();

        return redirect($this->route);
    }

    public function destroyer(callable $callback = null)
    {
        $this->model->delete();
        return redirect($this->route);
    }
}