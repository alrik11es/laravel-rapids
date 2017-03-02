<?php
namespace Laravel\Rapids;

class FormBuilder
{
    private $fields;
    private $action_url;

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
    public function build($method = 'get', $inline = false): \stdClass
    {
        /** @var \Collective\Html\FormBuilder $form */
        $form = resolve('form');
        $output_form = new \stdClass();
        $output_form->fields = array();

        $options = [
            'url' => $this->action_url,
            'method' => $method,
            'class' => ($inline) ? 'form-inline' : ''
        ];
        $output_form->open = $form->open($options);

        $output_form->message = null;
        foreach ($this->fields as $field) {

            $field_options = ['class' => 'form-control', 'placeholder' => $field->name];

            if($field->type == Field::TYPE_TEXT) {
                $field->output = $form->text($field->field_id, \Request::input($field->field_id), $field_options);
            }

            if($field->type == Field::TYPE_DATE){
                $field->output = $form->date($field->field_id, \Request::input($field->field_id), $field_options);
            }

            $output_form->fields[] = $field;

        }
        $output_form->submit = $form->submit(null, ['class'=>'btn btn-info']);
        $output_form->reset = $form->reset(null, ['class'=>'btn btn-default']);
        $output_form->close = $form->close();
        return $output_form;
    }
}