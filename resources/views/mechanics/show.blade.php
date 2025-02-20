@extends('layout')

@section('content')
    @livewire('back-button')
    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">Tecnico - {{ $mechanic->user->name }}
        {{ $mechanic->user->surname }}</h4>
    <div class="2xl:flex 2xl:space-x-4">
        <div class="flex gap-4 2xl:block 2xl:w-1/3 2xl:space-y-0">
            <div class="bg-white p-4 rounded-sm w-full">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Dati generali</span>
                </div>
                <div class="flex justify-between mb-4">
                    <img src="{{ asset($mechanic->user->image_path ?? 'images/placeholder.png') }}" alt="profile image"
                        class="w-20 h-20 rounded-full">
                    <div
                        class="bg-[#EDF8FB] h-10 w-6 p-1 flex items-center justify-center group hover:bg-[#66C0DB] duration-200 rounded-sm">
                        <button
                            onclick="Livewire.dispatch('openModal', { component: 'mechanic-form', arguments: { mechanicId:{{ $mechanic->id }} }})"
                            class="flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15.559" height="20.152"
                                viewBox="0 0 15.559 20.152" class="group-hover:fill-white">
                                <g id="noun-pencil-3690771" transform="translate(-7.171 -0.615)">
                                    <g id="Layer_19" data-name="Layer 19" transform="translate(7.48 0.946)">
                                        <!-- Apply fill and hover effects directly to the path -->
                                        <path id="Tracciato_755" data-name="Tracciato 755"
                                            d="M1.085,16.985a.43.43,0,0,0,.617.263l3.5-1.874a.43.43,0,0,0,.192-.21L9.939,4.493h0l.83-1.948a.429.429,0,0,0-.226-.563L5.977.035a.43.43,0,0,0-.564.227L.034,12.879a.431.431,0,0,0-.018.284ZM6.035.993,9.811,2.6,9.318,3.759,5.545,2.143Zm-.827,1.94L8.981,4.55,4.659,14.687l-2.892,1.55L.884,13.078Z"
                                            transform="matrix(0.966, 0.259, -0.259, 0.966, 4.477, 0)" fill="#66c0db"
                                            class="group-hover:fill-white transition-colors duration-200" />
                                    </g>
                                </g>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="px-2 space-y-4">
                    <div>
                        <span class="text-[#808080] text-[15px]">Nome Cognome: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->user->name }}
                            {{ $mechanic->user->surname }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Indirizzo email: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->user->email }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Codice Fiscale: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->user->cdf }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Password: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->plain_password }}</span>
                    </div>
                  {{--<div>
                        <span class="text-[#808080] text-[15px]">Filiale: </span>
                        <span class="text-[#222222] text-[15px]">{{ $mechanic->branch }}</span>
                    </div> --}}
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
            <div class="bg-white p-4 rounded-sm w-full">
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
        <div x-data="{ activeTab: 'tab1' }" class="2xl:w-2/3 mt-4 2xl:mt-0 bg-white p-4 mb-12 2xl:mb-0">
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
                @livewire('show-orders-mechanic', ['orders' => $mechanic->orders->where('state', 'Riparata')])
            </div>
            <div x-show="activeTab === 'tab2'" class="p-4">
                @livewire('show-orders-mechanic', ['orders' => $mechanic->orders->where('state', 'In lavorazione')])
            </div>
            <div x-show="activeTab === 'tab3'" class="p-4">
                @livewire('general-docs', ['docs' => $mechanic->user->docs, 'modelId' => $mechanic->user->id, 'isOrderModel' => 0])
            </div>
        </div>
    </div>
@endsection
