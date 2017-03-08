<?php
namespace Laravel\Rapids\Fields;

use Laravel\Rapids\Cell;

interface FieldInterface
{
    public function setCell(Cell $cell);
    public function render();
    public function operate();
}