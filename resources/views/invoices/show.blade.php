@extends('layout')

@section('content')
    @livewire('back-button')
    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">Dettaglio preventivo - {{ $invoice->id }}</h4>
    <div class="flex space-x-4">
        <div class="w-1/3 space-y-4">
            <div class="bg-white p-4 rounded-sm">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222] flex"> <img class="px-2"
                            src="{{ asset('images/icon profilo.svg') }}" alt="icon profilo">
                        Dati generali</span>
                </div>
                <div class="px-2 space-y-4">
                    {{-- @if ($invoice->user->hasRole('customer'))
                    
                    @else
                        mechanic
                    @endif --}}
                    <div>
                        <span class="text-[#808080] text-[15px]">Cliente: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Referente: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->customerInfo->admin_name }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Indirizzo Email:</span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->email }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Cellulare: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->cellphone }}</span>
                    </div>

                    <div>
                        <span class="text-[#808080] text-[15px]">Indirizzo: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->address }}</span>
                    </div>

                    <div>
                        <span class="text-[#808080] text-[15px]">Data creazione: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->created_at->format('d/m/Y') }}</span>
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
                        <span class="text-[#808080] text-[15px]">Nome azienda: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->name }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Ragione sociale: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->customerInfo->rag_sociale }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Partita IVA: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->customerInfo->iva }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">PEC: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->customerInfo->pec }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">SDI: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->customerInfo->sdi }}</span>
                    </div>

                    <div>
                        <span class="text-[#808080] text-[15px]">Indirizzo sede legale: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->customerInfo->legal_address }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Cap: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->cap }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Provincia: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->province }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Città: </span>
                        <span class="text-[#222222] text-[15px]">{{ $invoice->user->city }}</span>
                    </div>
                </div>
            </div>

        </div>
        <div class="w-2/3 bg-white p-4">
            <div class="p-1 bg-[#F2F1FB] mb-4">
                <span class="text-[15px] text-[#222222] flex"> <img class="px-2"
                        src="{{ asset('images/preventivi icona.svg') }}" alt="general data icon"> Dati fattura</span>
            </div>
            <div class="mb-4">
                <span class="text-[#808080] text-[13px]">Stato: </span>
                <span
                    class="px-2.5 py-0.5 font-semibold rounded-md
                    text-[13px]
                   @if ($invoice->is_closed) bg-[#DC0814] text-white
                   @else
                       bg-[#EFF7E9] text-[#7FBC4B] @endif
                    ">
                    {{ $invoice->is_closed ? 'Chiuso' : 'Aperto' }}
                </span>
            </div>
            <div class="border-dashed border border-[#E8E8E8] my-4"></div>
            <div class="grid grid-cols-3 gap-4">
                <!-- Row 1 -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">N° Fatture</label>
                    <input type="text" value="{{ $invoice->id }}"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">

                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Data</label>
                    <input type="text" value="{{ $invoice->created_at->format('d/m/Y') }}"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">

                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Imponibile</label>
                    <input type="text" value="{{ $invoice->price }} €"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">

                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Guadagno Tecnico</label>
                    <input type="text" value="{{ $invoice->iva }} €"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">

                </div>

            </div>

        </div>

    </div>
@endsection
