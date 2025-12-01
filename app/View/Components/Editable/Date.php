<?php

namespace App\View\Components\Editable;

class Date extends Base
{
    public $formattedValue;
    public $inputValue;

    public function __construct(
        $model,
        $field,
        $modelName,
        $label = null,
        $labelInline = false,
        $leftIcon = null
    ) {
        parent::__construct($model, $field, $modelName, $label, $labelInline, $leftIcon);

        $this->formattedValue = $model->{'formated_' . $field} ?? 'Nema';
        $this->inputValue = $model->{'input_formated_' . $field} ?? null;
    }

    public function render()
    {
        return view('components.editable.date');
    }
}
