<?php

namespace App\Livewire;

use App\Models\GeneralDoc;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class GeneralDocs extends Component
{
    public $docs;           
    public $originalDocs;   
    public $searchTerm;     
    public $userId;     
    protected $listeners = ['docAdded'];

    public function mount(Collection $docs, $userId)
    {
        $this->originalDocs = $docs; 
        $this->docs = $docs;         
        $this->userId = $userId;         
        $this->searchTerm = '';      
    }

    public function docAdded($docId)
    {
        $doc = GeneralDoc::findOrFail($docId);

        $this->docs->push($doc);
        $this->originalDocs->push($doc);
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
        return view('livewire.general-docs', [
            'docs' => $this->docs,
        ]);
    }
}
