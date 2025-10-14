<?php

namespace App\View\Components;

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
    
    public function render(): View|Closure|string
    {
        return view('components.delete-button');
    }
}