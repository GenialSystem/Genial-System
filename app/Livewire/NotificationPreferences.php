<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\NotificationPreference;

class NotificationPreferences extends Component
{
    public $silence_all;
    public $new_appointment;
    public $order_state_change;
    public $new_order_assigned;

    public function mount()
    {
        $preferences = Auth::user()->notificationPreferences;

        if ($preferences) {
            // Se le preferenze esistono, usa i valori salvati
            $this->new_appointment = $preferences->new_appointment;
            $this->order_state_change = $preferences->order_state_change;
            $this->new_order_assigned = $preferences->new_order_assigned;
           
            // Determina il valore di silence_all basandoti sui valori delle preferenze
            $this->silence_all = !$this->new_appointment && !$this->order_state_change && !$this->new_order_assigned;
        } else {
            // Se le preferenze non esistono, imposta tutte le notifiche su true e disattiva "Silenzia tutte"
            $this->new_appointment = true;
            $this->order_state_change = true;
            $this->new_order_assigned = true;
            $this->silence_all = false;
        }
    }


    public function updatedSilenceAll($value)
    {
        if ($value) {
            // Se "Silenzia tutte" è attivato, disattiva tutte le notifiche
            $this->new_appointment = false;
            $this->order_state_change = false;
            $this->new_order_assigned = false;
        } else {
            // Se "Silenzia tutte" è disattivato, attiva tutte le notifiche
            $this->new_appointment = true;
            $this->order_state_change = true;
            $this->new_order_assigned = true;
        }
        
        $this->savePreferences();
    }


    public function updated($propertyName)
    {
        if ($propertyName !== 'silence_all') {
            // Se una preferenza individuale viene attivata, disattiva "Silenzia tutte"
            $this->silence_all = !$this->new_appointment && !$this->order_state_change && !$this->new_order_assigned;
        }

        $this->savePreferences();
    }

    public function savePreferences()
    {
        $user = Auth::user();
        $user->notificationPreferences()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'new_appointment' => $this->new_appointment,
                'order_state_change' => $this->order_state_change,
                'new_order_assigned' => $this->new_order_assigned,
            ]
        );
        
    }

    public function render()
    {
        return view('livewire.notification-preferences');
    }
}
