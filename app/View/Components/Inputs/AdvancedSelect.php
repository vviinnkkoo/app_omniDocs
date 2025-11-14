<?php

namespace App\View\Components\Inputs;

use Illuminate\View\Component;

class AdvancedSelect extends Component
{
    public $name;
    public $label;
    public $items;
    public $grouped;
    public $children;   // NOVO: property ili closure za children
    public $placeholder;
    public $required;
    public $itemLabel;

    public function __construct(
        string $name,
        string $label = '',
        $items = [],
        bool $grouped = false,
        $children = null,         // npr. 'products' ili fn($group) => $group->whatever
        string $placeholder = 'Pretraži...',
        bool $required = true,
        $itemLabel = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->items = $items;
        $this->grouped = $grouped;
        $this->children = $children;
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->itemLabel = $itemLabel;
    }
}
