<?php
namespace Laravel\Rapids;

use Laravel\Rapids\Fields\CheckBoxGroup;
use Laravel\Rapids\Fields\FieldInterface;

class FormBuilder
{
    private $cells;
    private $action_url;
    private $method;

    private $forms = [
        Cell::TYPE_CHECKBOXGROUP => CheckBoxGroup::class
    ];

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function __construct()
    {
        $this->action_url = \Request::url();
    }

    public function setCells($cells)
    {
        $this->cells = $cells;
    }

    public function setActionUrl($action_url)
    {
        $this->action_url = $action_url;
    }

    /**
     * @return \stdClass
     */
    public function build($inline = false): \stdClass
    {
        /** @var \Collective\Html\FormBuilder $form */
        $form = resolve('form');
        $output_form = new \stdClass();
        $output_form->fields = array();

        $options = [
            'url' => $this->action_url,
            'method' => $this->method,
            'class' => ($inline) ? 'form-inline' : ''
        ];
        $output_form->open = $form->open($options);

        $output_form->message = null;
        foreach ($this->cells as $cell) {

            $field_options = ['class' => 'form-control', 'placeholder' => $cell->name];
            if(isset($cell->options)) {
                $field_options = array_merge($field_options, $cell->options);
            }

            if(!isset($cell->value)){
                $cell->value = '';
            }

            $type = $cell->type;

            if(in_array($type, $this->forms)){
                /** @var FieldInterface $field */
                $type_field = $this->cells[$type];
                $field = new $type_field();
                $field->setCell($cell);
                $field->info = $field;
                $cell->output = $field->render();
            } else {
                $cell->output = $form->$type($cell->field_id, $cell->value, $field_options);
            }

            $output_form->fields[] = $cell;
        }
        $output_form->submit = $form->submit(null, ['class'=>'btn btn-info']);
        $output_form->reset = $form->reset(null, ['class'=>'btn btn-default']);
        $output_form->close = $form->close();
        return $output_form;
    }
}