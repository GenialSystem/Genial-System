<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TopScrollbar extends Component
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        return view('components.top-scrollbar');
    }
}
