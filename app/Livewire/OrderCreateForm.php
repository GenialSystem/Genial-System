<?php

namespace App\Livewire;

use App\Models\CarPart;
use App\Models\CustomerInfo;
use App\Models\MechanicInfo;
use Livewire\Component;

class OrderCreateForm extends Component
{
    public  $car_sizes = ['Piccolo', 'Medio', 'Grande', 'Veicolo commerciale'];
    public $adminName = '';
    public $selectedCustomerId = null;
    public $brand = '';
    public $plate = '';
    public $mechanicIdFromEstimate;
    public function mount()
    {
        // Check if a customer_id, brand, and plate are passed via the query string and set them
        if ($customerId = request()->query('customer_id')) {
            $this->selectedCustomerId = $customerId;
            $customer = CustomerInfo::find($customerId);
            
            if ($customer) {
                $this->adminName = $customer->admin_name; 
            }
        }

        if ($brand = request()->query('brand')) {
            $this->brand = $brand; // Prepopulate brand
        }

        if ($plate = request()->query('plate')) {
            $this->plate = $plate; // Prepopulate plate
        }

        if ($mechanicIdFromEstimate = request()->query('mechanic_id')) {
            $this->mechanicIdFromEstimate = $mechanicIdFromEstimate;
        
            $mechanic = MechanicInfo::with('user:id,name,surname')
                        ->find($this->mechanicIdFromEstimate);
        
            if ($mechanic && $mechanic->user) {
                $data = [
                    'mechanic_id' => $mechanic->id,
                    'name'        => $mechanic->user->name,
                    'surname'     => $mechanic->user->surname,
                ];
        
                $this->dispatch('default_mechanic', $data);
            }
        }
        
    }
    
    public function render()
    {
        $customers = CustomerInfo::all();
        $mechanics = MechanicInfo::all();
        $parts = CarPart::all();
        return view('livewire.order-create-form', ['customers' => $customers, 'mechanics'=> $mechanics, 'parts' => $parts, 'car_sizes' => $this->car_sizes]);
    }
}
