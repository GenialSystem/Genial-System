<?php

namespace App\Livewire;

use App\Models\GeneralDoc;
use App\Models\OrderFile;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class GeneralDocs extends Component
{
    public $docs;           
    public $originalDocs;   
    public $searchTerm;     
    public $modelId;     
    public $isOrderModel;     
    protected $listeners = ['docAdded'];
   
    public function mount(Collection $docs, $modelId, $isOrderModel)
    {
        // dd($isOrderModel);
        $this->originalDocs = $docs; 
        $this->docs = $docs;         
        $this->isOrderModel = $isOrderModel;         
        $this->modelId = $modelId;         
        $this->searchTerm = '';      
        
    }

    public function docAdded($docId)
    {
        if ($this->isOrderModel) {
            $doc = OrderFile::findOrFail($docId);
        } else {
            $doc = GeneralDoc::findOrFail($docId);
        }
        

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
            'isOrderModel' => $this->isOrderModel
        ]);
    }
}
