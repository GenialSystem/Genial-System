<?php

namespace App\Livewire;

use App\Models\Archive;
use Livewire\Component;

class ArchiveSection extends Component
{
    public $archives;
    public $customerId;

    protected $listeners = ['archiveAdded']; 

    public function mount($archives, $customerId)
    {
        $this->archives = $archives; 
        $this->customerId = $customerId; 
    }

    public function archiveAdded($archiveId)
    {
        $archive = Archive::find($archiveId);
        // dd($archive);
        if ($archive) {
            $this->archives->push($archive);
        }
    }
    
    public function render()
    {
        return view('livewire.archive-section', ['archives' => $this->archives]);
    }
}
