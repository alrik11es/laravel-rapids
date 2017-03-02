<?php
namespace Laravel\Rapids\Widgets;

use Collective\Html\FormBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Laravel\Rapids\Facades\Widget;
use Laravel\Rapids\WidgetManager;

class DataFilter extends WidgetAbstract
{
    const TYPE_TEXT = 1;
    const TYPE_NUMERIC = 2;
    const TYPE_DATE = 3;

    private $data = [
        'actions' => null,
        'link' => null
    ];

    /** @var Collection */
    private $fields;

    public function __construct($query_or_filter)
    {
        parent::__construct($query_or_filter);
        $this->fields = collect([]);
    }

    public function add($field_id, $name, $type = self::TYPE_TEXT)
    {
        $field = new \stdClass();
        $field->name = $name;
        $field->field_id = $field_id;
        $field->type = $type;
        $field->has_error = '';
        $field->label =  $name;
        $field->star = '';
        $field->messages = '';
        $this->fields->push($field);
        $this->runFilter($field);
        return $this;
    }

    private function runFilter($field)
    {
        $field_query = Request::input($field->field_id);
        if (!empty($field_query) && $field->type == self::TYPE_TEXT) {
            $this->query = $this->query->where($field->field_id, 'LIKE', '%'.$field_query.'%');
        }
    }

    public function render()
    {
        $this->data['fields'] = $this->fields;
        $output_form = $this->createForm();

        $this->data['df'] = $output_form;
        $output = \View::make('rapids::dataform_inline', $this->data)->render();
        return $output;
    }

    /**
     * @return \stdClass
     */
    private function createForm(): \stdClass
    {
        /** @var FormBuilder $form */
        $form = resolve('form');
        $output_form = new \stdClass();
        $output_form->fields = array();
        $output_form->open = $form->open(['method' => 'get', 'class' => 'form-inline']);
        $output_form->message = null;
        foreach ($this->fields as $field) {
            $field->output = $form->text($field->field_id, Request::input($field->field_id), ['class' => 'form-control', 'placeholder' => $field->name]);
            $output_form->fields[] = $field;
        }
        $output_form->submit = $form->submit(null, ['class'=>'btn btn-info']);
        $output_form->reset = $form->reset(null, ['class'=>'btn btn-default']);
        $output_form->close = $form->close();
        return $output_form;
    }

}