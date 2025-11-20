<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdvancedSelect extends Component
{
    public $name;
    public $label;
    public $items;
    public $grouped;
    public $placeholder;
    public $required;
    public $renderItem;
    public $childrenKey;

    public function __construct(
        string $name,
        $items = [],
        string $label = '',
        bool $grouped = false,
        string $placeholder = '',
        bool $required = false,
        $renderItem = null,
        string $childrenKey = 'items'
    ) {
        $this->name = $name;
        $this->items = $items;
        $this->label = $label;
        $this->grouped = $grouped;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->renderItem = $renderItem;
        $this->childrenKey = $childrenKey;
    }

    public function render(): View
    {
        return view('components.inputs.advanced-select');
    }
}
