<?php

namespace App\Livewire;

use Livewire\Component;

class BackButton extends Component
{
    public function goBack()
    {
        return redirect()->back(); // Redirects to the previous page
    }

    public function render()
    {
        return view('livewire.back-button');
    }
}
