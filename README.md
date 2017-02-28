#Laravel Rapids
The rapid development made with laravel.

![alt tag](https://raw.githubusercontent.com/alrik11es/laravel-rapids/master/resources/images/laravel_rapids.png)

This library is intended for those developers who like to make applications as fast as possible.

## Installation

Add Rapids service provider `Laravel\Rapids\RapidsServiceProvider::class,` to your app.php config in Laravel.

## Usage

```php
<?php
$data_grid = new DataGrid(Post::query());
$data_grid->add('id', 'id');
$data_grid->add('title', 'Name');
$data_grid->setActions('/grid/edit', 'modify|delete');

$grid = Widget::load($data_grid);

return view('grid', compact('grid'));

```
This will generate a simple datagrid with modify/delete options.
