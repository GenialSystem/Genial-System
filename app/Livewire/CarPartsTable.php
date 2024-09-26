<?php

namespace App\Livewire;

use Livewire\Component;

class CarPartsTable extends Component
{
    public $order;

    public function mount($order)
    {
        $this->order = $order;
    }

    public function updatePivotField($partId, $field, $value)
    {
        $part = $this->order->carParts()->where('car_part_id', $partId)->first();

        $part->pivot->$field = $value;
        $part->pivot->save();
    }

    public function render()
    {
        return view('livewire.car-parts-table', [
            'carParts' => $this->order->carParts,
        ]);
    }
}
