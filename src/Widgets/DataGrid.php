<?php
namespace Laravel\Rapids\Widgets;

class DataGrid implements WidgetInterface
{
    private $fields;
    private $query;
    private $output;
    private $pagination_limit;
    private $actions;

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

    public function setPaginationLimit($limit = 100)
    {
        $this->pagination_limit = $limit;
    }

    public function setActions($url, $actions = 'modify|delete')
    {
        $this->actions = explode('|', $actions);
        $this->actions_url = $url;
    }

    public function render()
    {
        $fields = $this->fields;
        $paginator = $this->query->paginate($this->pagination_limit);
        $actions = $this->actions;
        $url = $this->actions_url;

        $this->output = \View::make('rapids::grid.datagrid', compact('fields', 'paginator', 'actions', 'url'))->render();
        return $this->output;
    }

}