<?php
namespace Laravel\Rapids;

class FormBuilder
{
    private $fields;
    private $action_url;
    private $method;

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

    public function setFields($fields)
    {
        $this->fields = $fields;
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
        foreach ($this->fields as $field) {

            $field_options = ['class' => 'form-control', 'placeholder' => $field->name];
            $field_options = array_merge($field_options, $field->options);

            $type = $field->type;
            $field->output = $form->$type($field->field_id, $field->value, $field_options);

            $output_form->fields[] = $field;

        }
        $output_form->submit = $form->submit(null, ['class'=>'btn btn-info']);
        $output_form->reset = $form->reset(null, ['class'=>'btn btn-default']);
        $output_form->close = $form->close();
        return $output_form;
    }
}