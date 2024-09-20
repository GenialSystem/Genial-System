<?php

namespace App\Livewire;

use App\Models\CarPart;
use App\Models\CustomerInfo;
use App\Models\MechanicInfo;
use Livewire\Component;

class OrderCreateForm extends Component
{
   public  $car_sizes = ['Piccolo', 'Medio', 'Grande', 'Veicolo commerciale'];
    public function render()
    {
        $customers = CustomerInfo::all();
        $mechanics = MechanicInfo::all();
        $parts = CarPart::all();
        return view('livewire.order-create-form', ['customers' => $customers, 'mechanics'=> $mechanics, 'parts' => $parts, 'car_sizes' => $this->car_sizes]);
    }
}
