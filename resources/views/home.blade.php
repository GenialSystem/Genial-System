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
        if (Auth::user()->roles->pluck('name')[0] === 'customer') {
            # code...
            $nonAdminItems = [
                [
                    'color' => 'bg-[#FFCD5D]',
                    'image' => 'images/commesse_icona.svg',
                    'title' =>
                        Auth::user()->customerInfo->finished_cars_count + Auth::user()->customerInfo->queued_cars_count,
                    'subtitle' => 'Numero totale riparazioni auto grandinate',
                ],
                [
                    'color' => 'bg-[#5E66CC]',
                    'image' => 'images/tools.svg',
                    'title' => Auth::user()->customerInfo->queued_cars_count,
                    'subtitle' => 'Riparazioni in lavorazione',
                ],
                [
                    'color' => 'bg-[#68C9BB]',
                    'image' => 'images/car.svg',
                    'title' => Auth::user()->customerInfo->finished_cars_count,
                    'subtitle' => 'Riparazioni concluse',
                ],
            ];
        }

    @endphp
    <!-- w-1/3  w-1/5 -->
    <div>
        <h2 class="text-2xl font-bold mb-4">Home</h2>
        @role('admin')
            <div class="grid grid-cols-5 gap-4">
                @foreach ($items as $index => $item)
                    <div
                        class="2xl:flex 2xl:justify-start justify-center bg-white p-4 rounded-md
        shadow-[0px_0px_11px_rgba(116,116,116,0.09)]">

                        <div
                            class="rounded-md {{ $item['color'] }} 2xl:mr-4 p-4 w-16 h-16 mb-5 flex place-items-center 2xl:justify-normal justify-center 2xl:m-0 mx-auto">
                            <img src="{{ asset($item['image']) }}" alt="" />
                        </div>
                        <div class="place-content-center 2xl:text-start text-center">
                            <span class="font-semibold text-xl">{{ $item['title'] }}</span>
                            <br>
                            <span class="text-[#464646] text-[13px]">{{ $item['subtitle'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endrole
        @role('customer')
            <div class="grid grid-cols-3 gap-4">
                @foreach ($nonAdminItems as $index => $item)
                    <div
                        class="2xl:flex 2xl:justify-start justify-center bg-white p-4 rounded-md
    shadow-[0px_0px_11px_rgba(116,116,116,0.09)]">

                        <div
                            class="rounded-md {{ $item['color'] }} 2xl:mr-4 p-4 w-16 h-16 mb-5 flex place-items-center 2xl:justify-normal justify-center 2xl:m-0 mx-auto">
                            <img src="{{ asset($item['image']) }}" alt="" />
                        </div>
                        <div class="place-content-center 2xl:text-end text-center w-full">
                            <span class="font-semibold text-xl">{{ $item['title'] }}</span>
                            <br>
                            <span class="text-[#464646] text-[13px]">{{ $item['subtitle'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="2xl:flex 2xl:h-[500px] mb-10 gap-4">
                @livewire('estimate-request-form')
                @livewire('work-chart')
            </div>
        @endrole
        @role('admin')
            <div class="2xl:flex 2xl:space-x-6">
                @livewire('order-production-chart')

                @livewire('sales-chart')
            </div>
        @endrole
        @role('mechanic')
            @livewire('mechanic-home-banner')
        @endrole

        @livewire('order-table', key(str()->random(10)))
    </div>
@endsection
