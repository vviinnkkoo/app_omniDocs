<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;
use Illuminate\View\View;

class PillowButtons extends Component
{
    public $name;
    public $items;
    public $type; // radio ili checkbox
    public $required;
    public $labelClass;
    public $inputClass;
    public $wrapperClass;
    public $labelText;

    /**
     * @param string $name
     * @param array $items
     * @param string $type
     * @param bool $required
     * @param string $labelClass
     * @param string $inputClass
     * @param string $wrapperClass
     * @param string $labelText
     */
    public function __construct(
        string $name,
        array $items = [],
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
