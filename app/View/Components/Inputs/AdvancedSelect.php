<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdvancedSelect extends Component
{
    public $name;
    public $items;
    public $grouped;
    public $placeholder;
    public $required;
    public $renderItem;

    public function __construct(
        $name,
        $items,
        $grouped = false,
        $placeholder = '',
        $required = false,
        $renderItem = null
    ) {
        $this->name = $name;
        $this->items = $items;
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
