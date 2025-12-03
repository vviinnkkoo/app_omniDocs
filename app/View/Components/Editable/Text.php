<?php

namespace App\View\Components\Editable;

class Text extends Base
{
    public $value;

    public function __construct(
        $model,
        $field,
        $modelName,
        $value = null,
        $label = null,
        $labelInline = false,
        $leftIcon = null,
        $suffix = null
    ) {
        parent::__construct($model, $field, $modelName, $label, $labelInline, $leftIcon, $suffix);
        $this->value = $value;
    }

    public function render()
    {
        return view('components.editable.text');
    }
}
