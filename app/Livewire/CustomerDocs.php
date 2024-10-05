<?php
namespace App\Livewire;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CustomerDocs extends Component
{
    public $docs;           
    public $originalDocs;   
    public $searchTerm;     

    public function mount(Collection $docs)
    {
        $this->originalDocs = $docs; 
        $this->docs = $docs;         
        $this->searchTerm = '';      
    }

    public function updatedSearchTerm()
    {
        if (trim($this->searchTerm) === '') {
            $this->docs = $this->originalDocs;
        } else {
            $this->docs = $this->originalDocs->filter(function ($item) {
                return stripos($item->name, $this->searchTerm) !== false; // Case-insensitive search
            });
        }
    }

    public function render()
    {
        return view('livewire.customer-docs', [
            'docs' => $this->docs,
        ]);
    }
}
