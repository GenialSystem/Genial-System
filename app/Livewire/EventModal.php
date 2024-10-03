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
    public $selectedMechanics = [];

    protected $listeners = ['addMechanic', 'removeMechanic'];

    public function addMechanic($id)
    {
        if (!in_array($id, $this->selectedMechanics)) {
            $this->selectedMechanics[] = $id;
            return;
        }
    }

    public function removeMechanic($id)
    {
        $this->selectedMechanics = array_diff($this->selectedMechanics, [$id]);
    }

       
    public function mount($event = null)
    {
        if ($event) {
            $this->event = $event;
            $this->name = $event->name;
            $this->date = $event->date;
            $this->start_time = $event->start_time;
            $this->end_time = $event->end_time;
        }
    }

    public function createEvent()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'selectedMechanics' => 'required|array' // Ensure selectedMechanics is an array
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

        // Close the modal
        $this->dispatch('closeModal');
        return redirect()->route('calendar.index');
    }


    // Method to handle event update
    public function updateEvent()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $this->event->update([
            'name' => $this->name,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ]);
    }

    public function render()
    {
        $mechanics = MechanicInfo::all();

        return view('livewire.event-modal',['mechanics' => $mechanics]);
    }
}
