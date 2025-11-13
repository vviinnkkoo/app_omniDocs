<?php

namespace App\View\Components\Misc;

use Illuminate\View\Component;

class TablePagination extends Component
{
    public $items;

    public function __construct($items)
    {
        $this->items = $items;
    }

    public function render()
    {
        return view('components.misc.table-pagination');
    }
}