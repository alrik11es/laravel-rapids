<?php
namespace Laravel\Rapids\Widgets;

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

    public function __construct($query)
    {
        $this->query = $query;
        $this->data['fields'] = collect([]);
    }

    public function add($field_id, $name, $needs_order = false)
    {
        $field = new \stdClass();
        $field->name = $name;
        $field->field_id = $field_id;
        $field->needs_order = $needs_order;
        $this->data['fields']->push($field);
        return $this;
    }

    public function setPaginationLimit($limit = 50)
    {
        $this->pagination_limit = $limit;
    }

    public function setActions($url, $actions = 'modify|delete')
    {
        $this->data['actions'] = explode('|', $actions);
        $this->data['url'] = $url;
    }

    public function setLink($url, $value)
    {
        $link = new Link($url, $value, Link::TYPE_DEFAULT, true);
        $this->data['link'] = (new WidgetManager())->load($link);
    }

    /**
     * Gets the selected fields and returns for ordering operations
     * @param Request $request
     */
    private function returnOrderByInputValue(Request $request, $fields)
    {
//        foreach($this->fields as $field) {
//            $this->data[$field.'_url'] = $request->input('order') == 'asc'
//                ? $request->fullUrlWithQuery(['order' => 'desc', 'field' => $field])
//                : $request->fullUrlWithQuery(['order' => 'asc', 'field' => $field]);
//        }
    }

    public function render()
    {
        $this->data['paginator'] = $this->query->paginate($this->pagination_limit);
        $output = \View::make('rapids::grid.datagrid', $this->data)->render();
        return $output;
    }

}