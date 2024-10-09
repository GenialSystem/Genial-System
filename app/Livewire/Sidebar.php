<?php

namespace App\Livewire;

use Livewire\Component;

class Sidebar extends Component
{
    public $isCollapsed = false;

    protected $listeners = ['toggleSidebar' => 'toggleSidebar'];

    public function mount()
    {
        // Restore state from session or keep it default
        $this->isCollapsed = session('sidebar.collapsed', false);
    }

    public function toggleSidebar($collapsed)
    {
        $this->isCollapsed = $collapsed;
        session(['sidebar.collapsed' => $collapsed]); // Save to session
    }

    public function render()
    {
        return view('livewire.sidebar', ['isCollapsed' => $this->isCollapsed]);
    }
}
