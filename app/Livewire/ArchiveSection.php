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
        // Sort the passed-in archives by 'date' descending
        $this->archives = $archives->sortByDesc('date');
        $this->customerId = $customerId;

        // Debugging to check the sorted collection
        // dd($this->archives);
    }


    public function archiveAdded($archiveId)
    {
        $archive = Archive::find($archiveId);
        if ($archive) {
            // Add the new archive to the collection
            $this->archives->push($archive);
            
            // Sort the collection by 'created_at' descending
            $this->archives = $this->archives->sortByDesc('date')->values();
        }
    }
    
    public function render()
    {
        return view('livewire.archive-section', ['archives' => $this->archives]);
    }
}