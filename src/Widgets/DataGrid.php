<?php
namespace Laravel\Rapids\Widgets;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Laravel\Rapids\Facades\Widget;
use Laravel\Rapids\WidgetManager;

class DataGrid extends WidgetAbstract
{
    private $data = [
        'actions' => null,
        'link' => null
    ];
    private $query;
    private $pagination_limit;
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

    public function setPaginationLimit($limit = 50)
    {
        $this->pagination_limit = $limit;
        return $this;
    }

    public function setActions($url, $actions = 'modify|delete')
    {
        $this->data['actions'] = explode('|', $actions);
        $this->data['url'] = $url;
        return $this;
    }

    public function setLink($url, $value)
    {
        $link = new Link($url, $value, Link::TYPE_DEFAULT, true);
        $this->data['link'] = (new WidgetManager())->load($link);
        return $this;
    }

    /**
     * Gets the selected fields and returns for ordering operations
     * @param Request $request
     */
    private function runOrders()
    {
        $this->query;
//        $field = Request::input('field');
//        $order = Request::input('order');

        foreach($this->fields as $field) {
            if($field->needs_order){
//                $field = Request::input('field')
            }
//            $this->data[$field.'_url'] = $request->input('order') == 'asc'
//                ? $request->fullUrlWithQuery(['order' => 'desc', 'field' => $field])
//                : $request->fullUrlWithQuery(['order' => 'asc', 'field' => $field]);
        }
    }

    public function addTransformation($field_id, callable $callback)
    {
        $collection = $this->fields->where('field_id', $field_id);

        foreach($collection as $key => $item){
            $item->transformation = $callback;
            $this->fields->offsetSet($key, $item);
        }
        return $this;
    }

    private function runValueTransformations()
    {
        /** @var Collection $results */
        $results = $this->query->paginate($this->pagination_limit);
        foreach($results as $key => $value){
            foreach($this->fields as $field){
                $field_name = $field->field_id;
                if(isset($field->transformation) && is_callable($field->transformation) && isset($value->$field_name)) {
                    $value->$field_name = ($field->transformation)($value->$field_name);
                }
            }
        }
        return $results;
    }

    public function render()
    {
        $this->data['fields'] = $this->fields;
//        $this->runOrders();
        $this->data['paginator'] = $this->runValueTransformations();
        $output = \View::make('rapids::grid.datagrid', $this->data)->render();
        return $output;
    }

}