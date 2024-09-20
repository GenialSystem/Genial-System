@extends('layout')

@section('content')
    @livewire('back-button')
    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">Dettaglio preventivo - {{ $estimate->id }}</h4>
    <div class="flex space-x-4">
        <div class="w-1/3 space-y-4">
            <div class="bg-white p-4 rounded-sm">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222] flex"> <img class="px-2"
                            src="{{ asset('images/icon profilo.svg') }}" alt="icon profilo">
                        Dati generali</span>
                </div>
                <div class="px-2 space-y-4">
                    <div>
                        <span class="text-[#808080] text-[15px]">Cliente: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->name }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Auto assegnate: </span>
                        <span
                            class="text-[#222222] text-[15px]">{{ $estimate->customer->customerInfo ? $estimate->customer->customerInfo->admin_name : 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Indirizzo email: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->email }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Cellulare</span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->cellphone }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">indirizzo: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->address }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Cap: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->cap }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Città: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->city }}</span>
                    </div>

                    <div>
                        <span class="text-[#808080] text-[15px]">Data creazione: </span>
                        <span
                            class="text-[#222222] text-[15px]">{{ $estimate->customer->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-sm">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222] flex"> <img class="px-2"
                            src="{{ asset('images/icon pagamento.svg') }}" alt="icon pagamento"> Dati di
                        fatturazione</span>
                </div>
                <div class="px-2 space-y-4">
                    <div>
                        <span class="text-[#808080] text-[15px]">Nome Società: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->name }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Ragione sociale: </span>
                        <span
                            class="text-[#222222] text-[15px]">{{ $estimate->customer->customerInfo->rag_sociale }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Partita IVA: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->customerInfo->iva }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">PEC: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->customerInfo->pec }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">SDI: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->customerInfo->sdi }}</span>
                    </div>

                    <div>
                        <span class="text-[#808080] text-[15px]">Indirizzo sede legale: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->address }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">CAP: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->cap }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Provincia: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->province }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Città: </span>
                        <span class="text-[#222222] text-[15px]">{{ $estimate->customer->city }}</span>
                    </div>
                </div>
            </div>

        </div>
        <div class="w-2/3 bg-white p-4">
            <div class="p-1 bg-[#F2F1FB] mb-4">
                <span class="text-[15px] text-[#222222] flex"> <img class="px-2"
                        src="{{ asset('images/preventivi icona.svg') }}" alt="general data icon"> Dati preventivo</span>
            </div>
            <div class="mb-4">
                <span class="text-[#808080] text-[13px]">Tipologia di lavorazione: </span>
                <span
                    class="px-2.5 py-0.5 font-semibold rounded-md
                    text-[13px] text-white
                    @switch($estimate->type)
                        @case('Preventivo combinato')
                            bg-[#7AA3E5]
                            @break
                        @case('Preventivo leva bolli')
                            bg-[#A892D1]
                            @break
                        @case('Carrozzeria')
                            bg-[#E68B69]
                            @break
                        @default
                            bg-white
                    @endswitch
                    ">
                    {{ $estimate->type }}
                </span>
            </div>

            <div>
                <span class="text-[#808080] text-[15px]">Responsabile: </span>
                <span
                    class="text-[#4453A5] text-[13px] bg-[#F2F1FB] font-semibold px-2.5 py-0.5 rounded-md">{{ $estimate->customer->customerInfo->admin_name }}</span>
            </div>
            <div class="border-dashed border border-[#E8E8E8] my-4"></div>
            <div class="grid grid-cols-3 gap-4">
                <!-- Row 1 -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">N° preventivo</label>
                    <input type="text" value="{{ $estimate->id }}"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">

                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Data</label>
                    <input type="text" value="{{ $estimate->created_at->format('d/m/Y') }}"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">

                </div>
            </div>
            <div class="border-dashed border border-[#E8E8E8] my-4"></div>

            <div class="grid grid-cols-3 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Tecnico</label>
                    <div class="relative">
                        <input type="text"
                            class="mt-1 block w-full pointer-events-none pl-12 px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none relative">
                        <span
                            class="flex place-items-center absolute inset-y-0 left-0 h-[70%] bg-[#F2F1FB] text-[#4453A5] font-semibold rounded-md ml-2 my-auto px-2 text-[13px]">Nicola
                            Nonini &times;</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Guadagno Tecnico</label>
                    <input type="text" value="35%"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">

                </div>

            </div>
            <div class="border-dashed border border-[#E8E8E8] my-4"></div>
            <span class="text-sm font-medium text-[#9F9F9F] text-[13px]">Prezzo :</span>
            <span class="bg-[#66C0DB] px-4 py-0.5 rounded-2xl text-white text-[15px] font-semibold">{{ $estimate->price }}
                €</span>
        </div>

    </div>
@endsection
