<?php

namespace App\View\Components\EditableDate;

use Illuminate\View\Component;

class Date extends Component
{
    public $label;
    public $model;
    public $field;
    public $modelName;

    public function __construct($label, $model, $field, $modelName)
    {
        $this->label = $label;
        $this->model = $model;
        $this->field = $field;
        $this->modelName = $modelName;
    }

    public function render()
    {
        return view('components.editable.date');
    }
}