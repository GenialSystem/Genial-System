@extends('layout')

@section('content')
    @php
        // List items for admin role
        $items = [
            [
                'color' => 'bg-[#5E66CC]',
                'image' => 'images/car.svg',
                'title' => $workingOrdersCount,
                'subtitle' => 'Totale Auto in lavorazione',
            ],
            [
                'color' => 'bg-[#68C9BB]',
                'image' => 'images/tools.svg',
                'title' => $newOrdersCount,
                'subtitle' => 'Totale Auto da riparare',
            ],
            [
                'color' => 'bg-[#66C0DB]',
                'image' => 'images/pdf.svg',
                'title' => $estimates,
                'subtitle' => 'Totale preventivi',
            ],
            [
                'color' => 'bg-[#FFCD5D]',
                'image' => 'images/client.svg',
                'title' => $customers,
                'subtitle' => 'Numero Clienti',
            ],
            [
                'color' => 'bg-[#FDA254]',
                'image' => 'images/tecnici.svg',
                'title' => $mechanics,
                'subtitle' => 'Numero Tecnici',
            ],
        ];

        // List items for non-admin roles
        $nonAdminItems = [
            [
                'color' => 'bg-[#FF7F50]',
                'image' => 'img6.png',
                'title' => '1.234',
                'subtitle' => 'Numero totale riparazioni auto grandinate',
            ],
            [
                'color' => 'bg-[#48D1CC]',
                'image' => 'img7.png',
                'title' => '567',
                'subtitle' => 'Riparazioni in lavorazione',
            ],
            [
                'color' => 'bg-[#FFD700]',
                'image' => 'img8.png',
                'title' => '789',
                'subtitle' => 'Riparazioni concluse',
            ],
        ];

        // Use the correct list based on the role
        $items = auth()->user()->hasRole('admin') ? $items : $nonAdminItems;
    @endphp
    <!-- w-1/3  w-1/5 -->
    <div class="">
        <h2 class="text-2xl font-bold mb-4">Home</h2>
        <div class="flex w-full">
            @foreach ($items as $index => $item)
                <div
                    class="w-1/{{ count($items) }} h-24 flex bg-white p-4 rounded-md
        shadow-[0px_0px_11px_rgba(116,116,116,0.09)]
        @if (!$loop->first) ml-2 @endif 
        @if (!$loop->last) mr-2 @endif">
                    <!-- Content goes here -->
                    <div class="rounded-md {{ $item['color'] }} p-4 mr-4 w-16 h-16 flex place-items-center justify-center">
                        <img src="{{ asset($item['image']) }}" alt="" />
                    </div>
                    <div class="place-content-center">
                        <span class="font-semibold text-xl">{{ $item['title'] }}</span>
                        <br>
                        <span class="text-[#464646] text-[13px]">{{ $item['subtitle'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex space-x-6">
            @livewire('order-production-chart')

            @livewire('sales-chart')
        </div>
        @role('admin')
            @livewire('order-table', key(str()->random(10)))
        @endrole

    </div>
@endsection
