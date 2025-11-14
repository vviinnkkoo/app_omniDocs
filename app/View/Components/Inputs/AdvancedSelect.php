<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdvancedSelect extends Component
{
    public $name;
    public $label;        // dodano
    public $items;
    public $grouped;
    public $placeholder;
    public $required;
    public $renderItem;

    public function __construct(
        string $name,
        $items = [],
        string $label = '',            // dodano i postavljeno poslije items radi retro kompatibilnosti
        bool $grouped = false,
        string $placeholder = '',
        bool $required = false,
        $renderItem = null
    ) {
        $this->name = $name;
        $this->items = $items;
        $this->label = $label;               // assign
        $this->grouped = $grouped;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->renderItem = $renderItem;
    }

    public function render(): View
    {
        return view('components.inputs.advanced-select');
    }
}
