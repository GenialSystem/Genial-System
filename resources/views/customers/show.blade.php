@extends('layout')

@section('content')
    @livewire('back-button')
    <h4 class="text-[22px] text-[#222222] font-semibold">Cliente - {{ $customer->user->name }} {{ $customer->user->surname }}
    </h4>

    <div class="flex space-x-4 mt-4">
        <div class="w-1/3 space-y-4">
            <div class="bg-white p-4 rounded-sm">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Dati generali</span>
                </div>
                <div class="px-2 space-y-4">
                    <div>
                        <span class="text-[#808080] text-[15px]">Cliente: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->user->name }}
                            {{ $customer->user->surname }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Auto assegnate: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->assigned_cars_count }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Auto riparate: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->finished_cars_count }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Auto in coda: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->queued_cars_count }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Città: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->user->city }}</span>
                    </div>
                    <hr class="my-3">
                    <div>
                        <span class="text-[#808080] text-[15px]">Responsabile: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->admin_name }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Indirizzo email: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->user->email }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Cellulare: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->user->cellphone }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Data creazione: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-sm">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Dati di fatturazione</span>
                </div>
                <div class="px-2 space-y-4">
                    <div>
                        <span class="text-[#808080] text-[15px]">Ragione sociale: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->rag_sociale }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Partita IVA: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->iva }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">PEC: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->pec }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">SDI: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->sdi }}</span>
                    </div>
                    <hr class="my-3">
                    <div>
                        <span class="text-[#808080] text-[15px]">Indirizzo sede legale: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->legal_address }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">CAP: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->user->cap }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Provincia: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->user->province }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Città: </span>
                        <span class="text-[#222222] text-[15px]">{{ $customer->user->city }}</span>
                    </div>
                </div>
            </div>
            {{-- <div class="bg-white p-4 rounded-sm">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Prossimo appuntamento</span>
                </div>
                <div class="h-8">

                    <a href="#"><button class="px-2 bg-[#1E1B58] text-white rounded-md text-sm h-full">+
                            Aggiungi appuntamento</button></a>
                </div>
            </div> --}}
        </div>
        <div x-data="{ activeTab: 'tab1' }" class="w-2/3 bg-white p-4">
            <div class="flex space-x-4 mb-4">
                <button @click="activeTab = 'tab1'"
                    :class="activeTab === 'tab1' ? 'border-[#4453A5] text-[#4453A5]' : 'border-[#F0F0F0] text-[#9F9F9F]'"
                    class="relative py-2 px-4 border-b-2 border-transparent focus:outline-none">
                    <span class="inline-block border-r border-transparent pr-2">
                        Storico
                    </span>
                </button>


                <button @click="activeTab = 'tab2'"
                    :class="activeTab === 'tab2' ? 'border-[#4453A5] text-[#4453A5]' : 'border-[#F0F0F0] text-[#9F9F9F]'"
                    class="py-2 px-4 border-b-2 focus:outline-none">
                    Documenti generali
                </button>
                <button @click="activeTab = 'tab3'"
                    :class="activeTab === 'tab3' ? 'border-[#4453A5] text-[#4453A5]' : 'border-[#F0F0F0] text-[#9F9F9F]'"
                    class="py-2 px-4 border-b-2 focus:outline-none">
                    Schede lavori
                </button>
                <button @click="activeTab = 'tab4'"
                    :class="activeTab === 'tab4' ? 'border-[#4453A5] text-[#4453A5]' : 'border-[#F0F0F0] text-[#9F9F9F]'"
                    class="py-2 px-4 border-b-2 focus:outline-none">
                    Preventivi
                </button>
                <button @click="activeTab = 'tab5'"
                    :class="activeTab === 'tab5' ? 'border-[#4453A5] text-[#4453A5]' : 'border-[#F0F0F0] text-[#9F9F9F]'"
                    class="py-2 px-4 border-b-2 focus:outline-none">
                    Riepilogo fatture
                </button>
            </div>

            <div x-show="activeTab === 'tab1'" class="p-4">
                @livewire('archive-section', ['archives' => $customer->user->archivesCustomer, 'customerId' => $customer->id])
            </div>
            <div x-show="activeTab === 'tab2'" class="p-4">
                @livewire('general-docs', ['docs' => $customer->user->docs, 'userId' => $customer->user->id])
            </div>
            <div x-show="activeTab === 'tab3'" class="p-4">
                @livewire('customer-orders-table', ['orders' => $customer->user->orders])
            </div>
            <div x-show="activeTab === 'tab4'" class="p-4">
                @livewire('customer-estimates-table', ['estimates' => $customer->estimates])
            </div>
            <div x-show="activeTab === 'tab5'" class="p-4">
                @livewire('customer-invoices-table', ['invoices' => $customer->user->invoices])
            </div>
        </div>

    </div>
@endsection
