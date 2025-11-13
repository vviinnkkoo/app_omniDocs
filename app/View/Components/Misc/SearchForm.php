<?php

namespace App\View\Components\Misc;

use Illuminate\View\Component;

class SearchForm extends Component
{
    public $action;
    public $placeholder;
    public $buttonText;
    public $class;

    public function __construct(
        $action = null,
        $placeholder = 'Upiši traženi pojam...',
        $buttonText = 'Pretraži',
        $class = 'float-end mb-2'
    ) {
        $this->action = $action ?? url()->current();
        $this->placeholder = $placeholder;
        $this->buttonText = $buttonText;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.misc.search-form');
    }
}