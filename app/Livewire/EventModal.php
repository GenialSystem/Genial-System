<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\MechanicInfo;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class EventModal extends ModalComponent
{
    public Event $event; // To store the event model if updating
    public $name;
    public $date;
    public $start_time;
    public $end_time;
    public $selectedMechanics = []; // Ensure this is initialized as an array
    protected $messages = [
        'end_time.after' => 'L\'orario di fine non può essere precedente all\'orario di inizio',
        'selectedMechanics.required' => 'Selezionare almeno un meccanico'
    ];
    protected $listeners = ['addMechanic', 'removeMechanic'];

    public function mount($event = null)
    {
        if ($event) {
            $this->event = $event;
            $this->name = $event->name;
            $this->date = $event->date;
            $this->start_time = $event->start_time;
            $this->end_time = $event->end_time;

            // Initialize selectedMechanics as an array of mechanic IDs
            $this->selectedMechanics = $event->mechanics->pluck('id')->toArray(); 
        } else {
            $this->selectedMechanics = []; // Initialize as empty if no event
        }
    }

    public function addMechanic($id)
    {
        // Check if $id is a valid numeric value
        if (!empty($id) && is_numeric($id) && !in_array($id, $this->selectedMechanics)) {
            $this->selectedMechanics[] = $id; // Add mechanic ID to the array
        }
    }

    public function removeMechanic($id)
    {
        // Ensure $id is numeric and present in the selected mechanics
        if (is_numeric($id)) {
            $this->selectedMechanics = array_diff($this->selectedMechanics, [$id]);
        }
    }

    public function createEvent()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'selectedMechanics' => 'required|array', // Ensure selectedMechanics is an array
        ]);

        // Create the event
        $event = Event::create([
            'name' => $this->name,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);

        // Attach the selected mechanics to the event
        if (!empty($this->selectedMechanics)) {
            $event->mechanics()->attach($this->selectedMechanics);
        }

        return redirect()->route('calendar.index')->with('success', ['title' => 'Evento creato con successo', 'subtitle' => 'Sarà visibile sul calendario degli utenti selezionati']);
    }

    public function render()
    {
        $mechanics = MechanicInfo::all();
        return view('livewire.event-modal', ['mechanics' => $mechanics]);
    }
}
