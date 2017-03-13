<?php
namespace Laravel\Rapids\Widgets;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Laravel\Rapids\Cell;
use Laravel\Rapids\Facades\Widget;
use Laravel\Rapids\Fields\FieldInterface;
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
    private $cells;

    /** @var Model */
    private $model;
    private $route;
    private $request;

    public function __construct($model, $resource_route = null)
    {
        $this->route = $resource_route;
        $this->model = $model;
        $this->cells = collect([]);
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function add($cell_id, $name = null, $type = Cell::TYPE_TEXT, $options = [])
    {
        $cell = new Cell();
        $cell->name = $name;
        $cell->field_id = $cell_id;
        $cell->type = $type;
        $cell->has_error = '';
        $cell->value = $this->model->$cell_id;
        $cell->model = $this->model;
        $cell->label =  $name;
        $cell->star = '';
        $cell->messages =  [];
        $cell->options = $options;
        $cell->req = true;
        $this->cells->push($cell);
        return $cell;
    }

    public function request($cell_id, $type = Cell::TYPE_TEXT, $options = [])
    {
        $cell = new Cell();
        $cell->field_id = $cell_id;
        $cell->type = $type;
        $cell->has_error = '';

        if(isset($options['format'])){
            $cell->format = $options['format'];
        }
        $cell->model = $this->model;
        $cell->messages =  [];
        $cell->req = true;
        $this->cells->push($cell);
        return $cell;
    }

    public function render()
    {
        $this->data['cells'] = $this->cells;

        $form = new FormBuilder();

        if(isset($this->model->id)){
            $form->setActionUrl($this->route.'/'.$this->model->id);
            $form->setMethod('patch');
        } else {
            $form->setActionUrl($this->route);
            $form->setMethod('post');
        }

        $form->setCells($this->cells);
        $output_form = $form->build();

        $this->data['df'] = $output_form;
        $output = \View::make('rapids::dataform', $this->data)->render();
        return $output;
    }

    public function operate()
    {
        foreach($this->cells as $cell) {
            $cell_id = $cell->field_id;
            if(isset($cell->callback) && is_callable($cell->callback)){
                $cell_value = ($cell->transformation)($this->model, Request::input($cell_id));
            } else {

                $type = $cell->type;
                if(class_exists($type)){
                    /** @var FieldInterface $field */
                    $field = new $type();
                    $field->setCell($cell);
                    $cell_value = $field->operate();
                } else if ($cell->type == Cell::TYPE_DATE) {
                    $cell_value = Carbon::createFromFormat($cell->format, Request::input($cell_id));
                } else {
                    $cell_value = Request::input($cell_id);
                }
            }

            if(\Schema::hasColumn($this->model->getTable(), $cell_id)){
                $this->model->$cell_id = $cell_value;
            }
        }
        $this->model->save();

        //After save
        foreach($this->cells as $cell) {
            $type = $cell->type;
            if(class_exists($type)) {
                /** @var FieldInterface $field */
                $field = new $type();
                $field->setCell($cell);
                $field->operateAfterSave();
            }
        }

        return redirect($this->route);
    }

    public function destroyer(callable $callback = null)
    {
        $this->model->delete();
        return redirect($this->route);
    }
}