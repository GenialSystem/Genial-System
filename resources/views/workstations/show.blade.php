@extends('layout')

@section('content')
    @livewire('back-button')
    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">Dettaglio postazione di lavoro</h4>

    <div class="xl:flex xl:space-x-4 space-y-4 xl:space-y-0">
        <div class="xl:w-1/3 space-y-4">
            <div class="bg-white p-4 rounded-sm">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Info generali postazione lavoro</span>
                </div>

                <div class="px-2 space-y-4">
                    <div>
                        <span class="text-[#808080] text-[15px]">Cliente: </span>
                        <span class="text-[#222222] text-[15px]">{{ $workstation->customer->user->name }}
                            {{ $workstation->customer->user->surname }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Responsabile: </span>
                        <span class="text-[#222222] text-[15px]">{{ $workstation->customer->admin_name }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Città: </span>
                        <span class="text-[#222222] text-[15px]">{{ $workstation->customer->user->city }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">N° Tecnici: </span>
                        <span class="text-[#222222] text-[15px]">{{ count($workstation->mechanics) }}</span>
                    </div>

                </div>
            </div>

        </div>
        <div class="xl:w-2/3 bg-white p-4">
            @livewire('workstation-mechanic-table', ['mechanics' => $workstation->mechanics])
        </div>
    </div>
@endsection
