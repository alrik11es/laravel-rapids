<?php
namespace Laravel\Rapids\Widgets;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Laravel\Rapids\Facades\Widget;
use Laravel\Rapids\WidgetManager;

class DataGrid extends WidgetAbstract
{
    private $data = [
        'actions' => null,
        'link' => null,
        'filter' => null,
        'ord' => null
    ];

    private $pagination_limit;
    /** @var Collection */
    private $fields;
    private $filter;

    public function __construct($query_or_filter)
    {
        parent::__construct($query_or_filter);
        $this->filter = $query_or_filter;
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
     * Gets the selected fields and fix for ordering operations
     */
    private function runOrderBys()
    {
        foreach($this->fields as $field) {
            if($field->needs_order){
                $req =  Request::input('ord_'.$field->field_id);
                $this->data['ord']['ord_asc_'.$field->field_id] = Request::fullUrlWithQuery(['ord_'.$field->field_id => 'asc']);
                $this->data['ord']['ord_desc_'.$field->field_id] = Request::fullUrlWithQuery(['ord_'.$field->field_id => 'desc']);
                if(!empty($req)) {
                    if ($req == 'asc') {
                        $this->query = $this->query->orderBy($field->field_id, 'asc');
                    } else {
                        $this->query = $this->query->orderBy($field->field_id, 'desc');
                    }
                }
            }
        }
    }

    public function addTransformation($field_id, $name, callable $callback)
    {
        $this->add($field_id, $name);
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
        $this->runOrderBys();
        $this->data['paginator'] = $this->runValueTransformations();
        $this->data['filter'] = (new WidgetManager())->load($this->filter);
        $output = \View::make('rapids::grid.datagrid', $this->data)->render();
        return $output;
    }

}