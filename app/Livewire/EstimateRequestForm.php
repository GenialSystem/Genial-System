<?php

namespace App\Livewire;

use App\Mail\EstimateRequestMail;
use App\Models\Archive;
use App\Models\Estimate;
use App\Models\MechanicInfo;
use App\Models\User;
use App\Notifications\EstimateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class EstimateRequestForm extends Component
{
    public $description;
    public $date;
    public $selectedMechanic;
    public $brand;
    public $plate;
    public $immatricolazione;
    public $km;

    protected $rules = [
        'description' => 'required|string|min:4',
        'date' => 'required|date',
        'selectedMechanic' => 'required|exists:mechanic_infos,id',
        'brand' => 'required|string|max:255',
        'plate' => 'required|string|max:255',
        'immatricolazione' => 'required|string|max:255',
        'km' => 'required|integer|min:1',
    ];

    protected $messages = [
        'description.required' => 'La descrizione è obbligatoria.',
        'description.min' => 'La descrizione deve essere almeno 4 caratteri',
        'date.required' => 'La data è obbligatoria.',
        'selectedMechanic.required' => 'Devi selezionare un cliente.',
        'brand.required' => 'La marca è obbligatoria.',
        'plate.required' => 'La targa/telaio è obbligatoria.',
        'immatricolazione.required' => 'Il campo immatricolazione è obbligatorio.',
        'km.required' => 'Il numero di chilometri è obbligatorio.',
        'km.integer' => 'Il numero di chilometri deve essere un numero intero.',
    ];

    public function submit()
    {
        // Validate the form data
        try {
            DB::beginTransaction();
            $this->km = preg_replace('/[^\d]/', '', $this->km);
            $this->validate();

            // Send email to admin@example.com
            $emailData = [
                'customer' => Auth::user()->getFullName(),
                'description' => $this->description,
                'date' => $this->date,
                'mechanic' => MechanicInfo::find($this->selectedMechanic)->user->name . ' ' . MechanicInfo::find($this->selectedMechanic)->user->surname,
                'brand' => $this->brand,
                'plate' => $this->plate,
                'immatricolazione' => $this->immatricolazione,
                'km' => $this->km,
            ];

            $customerId = Auth::user()->customerInfo->id;
            $creator = Auth::user()->id;

            Estimate::create([
                'customer_id' => $customerId,
                'brand' => $this->brand,
                'plate' => $this->plate,
                'type' => 'Preventivo combinato',
                'state' => 'Nuovo',
                'price' => 0,
                'mechanic_id' => $this->selectedMechanic
            ]);

            Archive::create([
                'customer_id' => $customerId,
                'user_id' => $creator, 
                'date' => now(),
                'title' => 'Invio preventivo' 
            ]);

            Mail::to('info.genialsystem@gmail.com')->send(new EstimateRequestMail($emailData));
            
            $customerName = Auth::user()->getFullName();
            $user = User::find(1);
            $user->notify(new EstimateRequest($customerName));
            $this->dispatch('showBanner', 'Richiesta inviata con successo!', 'Quando verrà accettata ti arriverà una notifica.', 'success');
            $this->reset();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showBanner', 'Errore', $e->getMessage(), 'error');
            Log::info($e->getMessage());
        }
    }

    public function render()
    {
        $mechanics  = MechanicInfo::all();
        return view('livewire.estimate-request-form', ['mechanics' => $mechanics]);
    }
}
