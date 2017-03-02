<?php
namespace Laravel\Rapids\Widgets;

use Illuminate\Database\Eloquent\Builder;

abstract class WidgetAbstract
{
    /** @var Builder */
    protected $query;

    abstract public function render();

    public function __construct($query_or_filter)
    {
        $this->query = $query_or_filter;
        if($query_or_filter instanceof WidgetAbstract){
            $this->query = $query_or_filter->getQuery();
        }
    }

    public function getQuery()
    {
        return $this->query;
    }
}