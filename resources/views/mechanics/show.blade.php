@extends('layout')

@section('content')
    @livewire('back-button')
    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">Tecnico - {{ $mechanic->user->name }}</h4>
    <div class="flex space-x-4">
        <div class="w-1/3 space-y-4">
            <div class="bg-white p-4 rounded-sm">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Dati generali</span>
                </div>
                <div class="flex justify-between mb-4">
                    <div class="rounded-full w-20 h-20 bg-[#808080]"></div>
                    @livewire('mechanic-form', ['mechanicId' => $mechanic->id])
                </div>

                <div class="px-2 space-y-4">
                    <div>
                        <span class="text-[#808080] text-[15px]">Nome Cognome: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->user->name }} {{ $mechanic->surname }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Indirizzo email: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->user->email }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Codice Fiscale: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->cdf }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Password: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->plain_password }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Filiale: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->branch }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Data creazione: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->created_at->format('d/m/Y') }}</span>
                    </div>
                    <hr class="my-3">
                    <div>
                        <span class="text-[#808080] text-[15px]">Indirizzo: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->user->address }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Cap: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->user->cap }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Provincia: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->user->province }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Città: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->user->city }}</span>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-sm">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Info riparazioni</span>
                </div>
                <div class="px-2 space-y-4">
                    <div>
                        <span class="text-[#808080] text-[15px]">Auto riparate: </span>
                        <span class="text-[#222222] text-[15px]"> {{ $mechanic->repaired_count }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Auto in lavorazione: </span>
                        <span class="text-[#222222] text-[15px]"> {{ $mechanic->working_count }}</span>
                    </div>
                </div>
            </div>


        </div>
        <div x-data="{ activeTab: 'tab1' }" class="w-2/3 bg-white p-4">
            <div class="flex space-x-4 mb-4">
                <button @click="activeTab = 'tab1'"
                    :class="activeTab === 'tab1' ? 'border-[#4453A5] text-[#4453A5]' : 'border-[#F0F0F0] text-[#9F9F9F]'"
                    class="relative py-2 px-4 border-b-2 border-transparent focus:outline-none">
                    <span class="inline-block border-r border-transparent pr-2">
                        Auto riparate
                    </span>
                </button>


                <button @click="activeTab = 'tab2'"
                    :class="activeTab === 'tab2' ? 'border-[#4453A5] text-[#4453A5]' : 'border-[#F0F0F0] text-[#9F9F9F]'"
                    class="py-2 px-4 border-b-2 focus:outline-none">
                    Auto in lavorazione
                </button>
                <button @click="activeTab = 'tab3'"
                    :class="activeTab === 'tab3' ? 'border-[#4453A5] text-[#4453A5]' : 'border-[#F0F0F0] text-[#9F9F9F]'"
                    class="py-2 px-4 border-b-2 focus:outline-none">
                    Documenti generali
                </button>

            </div>

            <div x-show="activeTab === 'tab1'" class="p-4">
                @livewire('show-orders-mechanic', ['orders' => $mechanic->user->assignedOrders->where('state', 'Riparata')])
            </div>
            <div x-show="activeTab === 'tab2'" class="p-4">
                @livewire('show-orders-mechanic', ['orders' => $mechanic->user->assignedOrders->where('state', 'In lavorazione')])
            </div>
            <div x-show="activeTab === 'tab3'" class="p-4">
                Documenti
            </div>
        </div>
    </div>
@endsection
