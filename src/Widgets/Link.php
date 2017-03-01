<?php
namespace Laravel\Rapids\Widgets;

class Link extends WidgetAbstract
{
    public $link;
    public $value;
    public $type;
    public $pull_right;
    const TYPE_DEFAULT = 'default';

    public function __construct($link, $value, $type = self::TYPE_DEFAULT, $pull_right = false)
    {
        $this->link = $link;
        $this->value = $value;
        $this->type = $type;
        $this->pull_right = $pull_right;
    }

    public function render()
    {
        $link = $this->link;
        $value = $this->value;
        $type = $this->type;
        $pull_right = $this->pull_right;

        $this->output = \View::make('rapids::form.link', compact('link', 'value', 'type', 'pull_right'))->render();
        return $this->output;
    }

}