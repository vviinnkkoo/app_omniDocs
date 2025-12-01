<?php

namespace App\View\Components\Editable;

use Illuminate\View\Component;

class Text extends Component
{
    public $label;
    public $labelInline;
    public $leftIcon;
    public $model;
    public $field;
    public $modelName;
    public $value;
    public $simple;

    public function __construct(
        $model,
        $field,
        $modelName,
        $value,
        $label = null,
        $labelInline = false,
        $leftIcon = null,
        $simple = false
    ) {
        $this->label = $label;
        $this->labelInline = filter_var($labelInline, FILTER_VALIDATE_BOOLEAN);
        $this->leftIcon = $leftIcon;
        $this->model = $model;
        $this->field = $field;
        $this->modelName = $modelName;
        $this->value = $value;
        $this->simple = filter_var($simple, FILTER_VALIDATE_BOOLEAN);
    }

    public function render()
    {
        return view('components.editable.text');
    }
}