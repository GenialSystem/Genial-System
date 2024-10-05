<?php

namespace App\Livewire;

use App\Models\Archive; // Ensure you import the Archive model
use App\Models\User;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class ActivityModal extends ModalComponent
{
    public $customerId;    
    public $userId;        
    public $date;          
    public $title;         

    public function mount($customerId)
    {
        $this->customerId = $customerId; 
    }

    public function submit()
    {
        // Validate the input data
        $this->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'userId' => 'required|exists:users,id'
        ]);

       $archive = Archive::create([
            'customer_id' => $this->customerId,
            'user_id' => $this->userId,
            'date' => $this->date,
            'title' => $this->title,
        ]);
      
        $this->dispatch('closeModal'); 
        $this->dispatch('archiveAdded', $archive->id); 
    }

    public function render()
    {
        return view('livewire.activity-modal', [
            'users' => User::all()
        ]);
    }
}
