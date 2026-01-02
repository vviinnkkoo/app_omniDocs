<?php

namespace App\View\Components\Editable;

class Text extends Base
{
    public $value;
    public $suffix;
    public $nullable;

    public function __construct(
        $model,
        $field,
        $modelName,
        $value = null,
        $label = null,
        $labelInline = false,
        $leftIcon = null,
        $suffix = null,
        $nullable = false
    ) {
        parent::__construct($model, $field, $modelName, $label, $labelInline, $leftIcon, $suffix);
        $this->value = $value;
        $this->suffix = $suffix;
        $this->nullable = filter_var($nullable, FILTER_VALIDATE_BOOLEAN);
    }

    public function render()
    {
        return view('components.editable.text');
    }
}
