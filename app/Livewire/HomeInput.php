<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class HomeInput extends Component
{

    public $query = '';
    public $results = [];

    protected $updatesQueryString = ['query'];

    public function updatedQuery()
    {
        $this->filterResults();
    }

    public function filterResults()
    {
        if (empty($this->query)) {
            return;
        }

        // Esegui una query sugli utenti filtrando per nome o cognome
        $this->results = User::where('name', 'like', '%' . $this->query . '%')
            ->orWhere('surname', 'like', '%' . $this->query . '%')
            ->get()
            ->map(function ($user) {
                // Check if user has roles and retrieve the first role
                if ($user->roles->isNotEmpty()) {
                    $role = $user->roles[0]; // Get the first role

                    // Definisci la rotta in base al ruolo dell'utente
                    $roleString = $role->name === 'customer' ? 'Cliente' : 'Tecnico';
                    $roleRoute = $role->name === 'customer' ? 'customers.show' : 'mechanics.show';

                    // Check if the user has related info based on the role
                    $id = null;
                    if ($role->name === 'customer') {
                        $id = $user->customerInfo ? $user->customerInfo->id : null;
                    } else {
                        $id = $user->mechanicInfo ? $user->mechanicInfo->id : null;
                    }

                    // Ensure that ID is not null before creating the result array
                    if ($id) {
                        return [
                            'title' => $user->name . ' ' . $user->surname . ' - ' . $roleString,
                            'url' => route($roleRoute, $id)
                        ];
                    }
                }
                return null; // Return null if no valid result
            })
            ->filter() // Remove null values from the collection
            ->toArray();
    }


    public function render()
    {
        return view('livewire.home-input');
    }
}
