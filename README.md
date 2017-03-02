![alt tag](https://raw.githubusercontent.com/alrik11es/laravel-rapids/master/resources/images/laravel_rapids.png)

The rapid development made with laravel.

This library is intended for those developers who like to make applications as fast as possible.

## Requirements

* PHP >= 7.0.0
* Bootstrap on your project

## Installation

Add Rapids service provider `Laravel\Rapids\RapidsServiceProvider::class,` to your app.php config in Laravel.

## Usage

```php
<?php
$data_grid = new DataGrid(Post::query());
$data_grid->add('id', 'id');
$data_grid->add('title', 'Name');
$data_grid->setActions('/post/edit', 'modify|delete');

$grid = Widget::load($data_grid);

return view('grid', compact('grid'));
```

In your view:

```php
{!! $grid !!}
```
This will generate a simple datagrid with modify/delete options.

## Load any widget
Remember that to load any widget you're gonna need to execute `$widget_render = Widget::load($widget);`and will return the render of that widget. You only need to pass to blade as raw format `{!! $widget !!}`.
## Elements
In order to ease the usability of this tool we've created some widgets for you. Just to use on any place.
### DataGrid
The DataGrid is the bridge head of the computer development. You're gonna need it for everything. So lets take a look to the options that Rapids brings.

#####Startup
`$data_grid = new DataGrid(Post::query());` Start your DataGrid within an Eloquent model query

#####Adding new field to the table
`$data_grid->add('title', 'Title');` When you need to add a new field.

If you need that this field shows orderBy buttons. Set to true the third optional parameter.

#####Actions (Edit/Show/Delete)
`$data_grid->setActions('/post/edit', 'modify|delete');` This will add the action buttons in your DataGrid. Available options are "modify", "delete", "show"

#####Value transformations
Imagine the common case to have a price value over 50 to be painted as yellow.
```php
$grid_widget->addTransformation('price', 'Pricing', function($value){
    $result = $value;
    if($value > 50){
        $result = '<span style="background-color: yellow;">'.$value.'</span>';
    }
    return $result;
});
```
You can obviously make your own widget instead of adding raw html to the PHP code. Just pass any rendered string as result of the callback.

#####Row transformations
Sometimes you need to set some classes to the entire row. Use this type of transformation to do so.

#####Relations
Obviously you will need to add some relations to the table. The best way to achieve this is using a transformation. So for example:

```php
$grid_widget->addTransformation('categories', 'Categories', function($value){
    return $value->implode('name', ', ');
});
```

#### setActions
### DataForm

## Idea
The idea comes from other's people libraries that are really good. And the need to have a well written code base just to mess with.

* https://github.com/zofe/rapyd-laravel
* https://github.com/wutongwan/laravel-lego