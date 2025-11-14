<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;

class AdvancedRadio extends Component
{
    public $name;
    public $label;
    public $items;
    public $required;

    public function __construct(
        string $name,
        string $label = '',
        $items = [],
        bool $required = true
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->items = $items;
        $this->required = $required;
    }

    public function render()
    {
        return view('components.inputs.advanced-radio');
    }
}
