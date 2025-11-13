<?php

namespace App\View\Components\Buttons;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeleteButton extends Component
{
    public string $model;
    public int|string $id;
    
    public function __construct($id, $model)
    {
        $this->id = $id;
        $this->model = $model;
    }
    
    public function render()
    {
        return view('components.buttons.delete-button');
    }
}