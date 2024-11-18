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
    }
    
    public function render()
    {
        $customers = CustomerInfo::all();
        $mechanics = MechanicInfo::all();
        $parts = CarPart::all();
        return view('livewire.order-create-form', ['customers' => $customers, 'mechanics'=> $mechanics, 'parts' => $parts, 'car_sizes' => $this->car_sizes]);
    }
}
