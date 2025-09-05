<?php

namespace App\View\Components;

use Illuminate\View\Component;

class EditableDate extends Component
{
    public $model;
    public $field;
    public $modelName;

    public function __construct($model, $field, $modelName)
    {
        $this->model = $model;
        $this->field = $field;
        $this->modelName = $modelName;
    }

    public function render()
    {
        return view('components.editable-date');
    }
}