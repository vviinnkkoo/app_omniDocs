<?php

namespace App\View\Components\Editable;

use Illuminate\View\Component;

abstract class Base extends Component
{
    public $label;
    public $labelInline;
    public $leftIcon;
    public $model;
    public $field;
    public $modelName;

    public function __construct(
        $model,
        $field,
        $modelName,
        $label = null,
        $labelInline = false,
        $leftIcon = null
    ) {
        $this->label = $label;
        $this->labelInline = filter_var($labelInline, FILTER_VALIDATE_BOOLEAN);
        $this->leftIcon = $leftIcon;
        $this->model = $model;
        $this->field = $field;
        $this->modelName = $modelName;
    }
}
