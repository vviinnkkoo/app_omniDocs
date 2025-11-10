<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ModalButton extends Component
{
    public string $target;
    public string $toggle;
    public string $icon;
    public string $text;
    public string $class;

    /*
    ** Values used by this component can be optional or required:
    **     target  → REQUIRED
    **     toggle  → optional
    **     icon    → optional
    **     text    → optional
    **     class   → optional
    */

    public function __construct(
        string $target,
        ?string $toggle = null,
        ?string $icon = null,
        ?string $text = null,
        ?string $class = null
    ) {
        $this->target = $target;
        $this->toggle = $toggle ?? 'modal';
        $this->icon = $icon ?? 'bi bi-plus-circle-fill';
        $this->text = $text ?? 'Dodaj zapis';
        $this->class = $class ?? 'btn btn-primary mb-3';
    }

    public function render()
    {
        return view('components.modal-button');
    }
}
