<?php
namespace Laravel\Rapids\Fields;

use Illuminate\Support\Collection;
use Laravel\Rapids\Cell;

class CheckBoxGroup implements FieldInterface
{
    /** @var Cell */
    private $cell;

    public function setCell(Cell $cell)
    {
        $this->cell = $cell;
    }

    public function render()
    {
        list($relation, $element) = $this->explodeRelation();

        $output = '';
        /** @var \Collective\Html\FormBuilder $form */
        $form = resolve('form');
        foreach($this->cell->model->$relation()->getRelated()->all() as $item){

            $exists = $this->cell->model->$relation()->where('id','=',$item->id)->get();
            $mark = (count($exists)>0) ? true : false;
            $output .= $form->checkbox($relation.'[]', $item->id, $mark).'&nbsp;';
            $output .= $form->label($item->$element);
            $output .= '<br>';
        }
        return $output;
    }

    public function operate()
    {
        $relation = $this->cell->field_id;
        $result = \Request::input($relation);
        if (!is_array($result)) {
            $result = collect($result);
        }
        if ($this->cell->model->exists){
            $this->cell->model->$relation()->detach();
            foreach ($result as $item) {
                $this->cell->model->$relation()->attach($item, ($this->cell->pivot)($this->cell));
            }
        }
    }

    public function operateAfterSave()
    {
        $relation = $this->cell->field_id;
        $result = \Request::input($relation);
        if(!is_array($result)){
            $result = collect($result);
        }
        if (!$this->cell->model->exists) {
            foreach ($result as $item) {
                $this->cell->model->$relation()->attach($item, ($this->cell->pivot)($this->cell));
            }
        }
    }

    /**
     * @return array
     */
    private function explodeRelation(): array
    {
        $relation = $element = null;
        if (preg_match('/\./', $this->cell->field_id)) {
            $relations = explode('.', $this->cell->field_id);
            $relation = $relations[0];
            $element = $relations[1];
        }
        return array($relation, $element);
    }
}