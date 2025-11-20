<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;
use Illuminate\View\View;

class PillowButtons extends Component
{
    public $name;
    public $items;
    public $type;
    public $required;
    public $labelClass;
    public $inputClass;
    public $wrapperClass;
    public $labelText;

    public function __construct(
        string $name,
        $items,
        string $type = 'radio',
        bool $required = false,
        string $labelClass = 'btn btn-light btn-sm me-1 mb-1',
        string $inputClass = 'btn-check',
        string $wrapperClass = 'mb-3',
        string $labelText = ''
    ) {
        $this->name = $name;
        $this->items = $items;
        $this->type = $type;
        $this->required = $required;
        $this->labelClass = $labelClass;
        $this->inputClass = $inputClass;
        $this->wrapperClass = $wrapperClass;
        $this->labelText = $labelText;
    }

    public function render(): View
    {
        return view('components.inputs.pillow-buttons');
    }
}
