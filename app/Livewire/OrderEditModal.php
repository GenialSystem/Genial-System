<?php

namespace App\Livewire;

use App\Models\CarPart;
use App\Models\Order;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class OrderEditModal extends ModalComponent
{
    public Order $order;

    public static function modalMaxWidth(): string
    {
        return '5xl';
    }

    public static function destroyOnClose(): bool
    {
        return true;
    }

    public function render()
    {
        $parts = CarPart::all();
        $car_sizes = ['Piccolo', 'Medio', 'Grande', 'Veicolo commerciale'];
        $payments = ['Fattura non emessa','Fattura emessa', 'Fattura ricevuta', 'Saldato', 'Da saldare'];

        return view('livewire.order-edit-modal', ['car_sizes' => $car_sizes, 'parts' => $parts, 'payments' => $payments]);
    }
}
