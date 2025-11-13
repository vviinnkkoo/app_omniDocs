<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;

class AdvancedSelect extends Component
{
    public $name;
    public $label;
    public $items;
    public $grouped;
    public $placeholder;
    public $required;

    public function __construct(
        string $name,
        string $label = '',
        $items = [],
        bool $grouped = false,
        string $placeholder = 'Pretraži...',
        bool $required = true
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->items = $items;
        $this->grouped = $grouped;
        $this->placeholder = $placeholder;
        $this->required = $required;
    }

    public function render()
    {
        return view('components.inputs.advanced-select');
    }
}
