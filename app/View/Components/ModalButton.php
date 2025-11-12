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

    public function __construct(
        string $target,
        ?string $toggle = null,
        ?string $icon = null,
        ?string $text = null,
        ?string $extraClass = null,
        ?string $replaceClass = null
    ) {
        $defaultClasses = 'btn btn-primary mb-3';

        $this->target = $target;
        $this->toggle = $toggle ?? 'modal';
        $this->icon = $icon ?? 'bi bi-plus-lg';
        $this->text = $text ?? 'Dodaj zapis';

        if ($replaceClass) {
            $this->class = trim($replaceClass);
        } else {
            $this->class = trim($defaultClasses . ' ' . ($extraClass ?? ''));
        }
    }

    public function render()
    {
        return view('components.modal-button');
    }
}
