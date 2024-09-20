@extends('layout')

@section('content')
    @livewire('back-button')
    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">Dettaglio veicolo</h4>
    {{-- {{ $order->user->assignedOrders }} --}}

    <div class="flex space-x-4">
        <div class="w-1/3 space-y-4">
            <div class="bg-white p-4 rounded-sm">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Foto veicolo</span>
                </div>
                <div class="flex space-x-4">

                    <button
                        class="bg-[#F2F1FB] flex justify-center place-items-center px-2 duration-200 py-1 hover:bg-[#4453A5] text-[#4453A5] text-[13px] hover:text-white rounded-md group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="13.937" height="13.937"
                            viewBox="0 0 13.937 13.937">
                            <g id="Icon_feather-download" data-name="Icon feather-download"
                                transform="translate(-3.75 -3.75)">
                                <path id="Tracciato_630" data-name="Tracciato 630"
                                    d="M16.937,22.5v2.764a1.382,1.382,0,0,1-1.382,1.382H5.882A1.382,1.382,0,0,1,4.5,25.264V22.5"
                                    transform="translate(0 -9.709)" fill="none" stroke="#4453A5" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1.5"
                                    class="transition-colors duration-200 group-hover:stroke-white" />
                                <path id="Tracciato_631" data-name="Tracciato 631" d="M10.5,15l3.455,3.455L17.409,15"
                                    transform="translate(-3.236 -5.663)" fill="none" stroke="#4453A5"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    class="transition-colors duration-200 group-hover:stroke-white" />
                                <path id="Tracciato_632" data-name="Tracciato 632" d="M18,12.791V4.5"
                                    transform="translate(-7.282)" fill="none" stroke="#4453A5" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1.5"
                                    class="transition-colors duration-200 group-hover:stroke-white" />
                            </g>
                        </svg>
                        Scarica foto
                    </button><button
                        class="bg-[#F2F1FB] flex justify-center place-items-center px-2 duration-200 py-1 hover:bg-[#4453A5] text-[#4453A5] text-[13px] hover:text-white rounded-md group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="13.937" height="13.937"
                            viewBox="0 0 13.937 13.937">
                            <g id="Icon_feather-download" data-name="Icon feather-download"
                                transform="translate(-3.75 -3.75)">
                                <path id="Tracciato_630" data-name="Tracciato 630"
                                    d="M16.937,22.5v2.764a1.382,1.382,0,0,1-1.382,1.382H5.882A1.382,1.382,0,0,1,4.5,25.264V22.5"
                                    transform="translate(0 -9.709)" fill="none" stroke="#4453A5" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1.5"
                                    class="transition-colors duration-200 group-hover:stroke-white" />
                                <path id="Tracciato_631" data-name="Tracciato 631" d="M10.5,15l3.455,3.455L17.409,15"
                                    transform="translate(-3.236 -5.663)" fill="none" stroke="#4453A5"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    class="transition-colors duration-200 group-hover:stroke-white" />
                                <path id="Tracciato_632" data-name="Tracciato 632" d="M18,12.791V4.5"
                                    transform="translate(-7.282)" fill="none" stroke="#4453A5" stroke-linecap="round"
                                    stroke-linejoin="round" stroke-width="1.5"
                                    class="transition-colors duration-200 group-hover:stroke-white" />
                            </g>
                        </svg>
                        Allega
                    </button>
                </div>
                <div class="mt-5 h-[600px] bg-slate-400 rounded-sm"></div>
            </div>
        </div>
        <div x-data="{ activeTab: 'tab1' }" class="w-2/3 bg-white p-4">
            <div class="flex space-x-4 border-b">
                <button @click="activeTab = 'tab1'"
                    :class="activeTab === 'tab1' ? 'border-[#4453A5] text-[#4453A5]' :
                        'border-transparent text-[#9F9F9F]'"
                    class="relative py-2 px-4 border-b-2 focus:outline-none">
                    <span class="inline-block pr-2">
                        Info generali veicolo
                    </span>
                </button>

                <button @click="activeTab = 'tab2'"
                    :class="activeTab === 'tab2' ? 'border-[#4453A5] text-[#4453A5]' :
                        'border-transparent text-[#9F9F9F]'"
                    class="py-2 px-4 border-b-2 focus:outline-none">
                    Info riparazione
                </button>
                <button @click="activeTab = 'tab3'"
                    :class="activeTab === 'tab3' ? 'border-[#4453A5] text-[#4453A5]' :
                        'border-transparent text-[#9F9F9F]'"
                    class="py-2 px-4 border-b-2 focus:outline-none">
                    Documenti
                </button>

            </div>

            <div x-show="activeTab === 'tab1'" class="p-4">
                <div class="my-3">
                    <span class="text-[#808080] text-[15px]">Filiale: </span>
                    <span class="text-[#222222] text-[15px]"> Como</span>
                </div>
                <div class="my-3">
                    <span class="text-[#808080] text-[15px]">Targa: </span>
                    <span class="text-[#222222] text-[15px]"> {{ $order->plate }}</span>
                </div>

                <div class="my-3">
                    <span class="text-[#808080] text-[15px]">Marca/Modello: </span>
                    <span class="text-[#222222] text-[15px]"> {{ $order->brand }}</span>
                </div>
                <hr class="my-3">
                <span class="text-[#4453A5]">Dati cliente</span>
                <div class="my-3">
                    <span class="text-[#808080] text-[15px]">Cliente: </span>
                    <span class="text-[#222222] text-[15px]"> {{ $order->customer->name }}</span>
                </div>
                <div class="my-3">
                    <span class="text-[#808080] text-[15px]">Responsabile: </span>
                    <span class="text-[#222222] text-[15px]">
                        {{ $order->customer->customerInfo->admin_name ?? 'N/A' }}</span>
                </div>
                <div class="my-3">
                    <span class="text-[#808080] text-[15px]">Indirizzo email: </span>
                    <span class="text-[#222222] text-[15px]"> {{ $order->customer->email }}</span>
                </div>
                <div class="my-3">
                    <span class="text-[#808080] text-[15px]">Cellulare: </span>
                    <span class="text-[#222222] text-[15px]"> {{ $order->customer->cellphone }}</span>
                </div>
                <div class="my-3">
                    <span class="text-[#808080] text-[15px]">Indirizzo: </span>
                    <span class="text-[#222222] text-[15px]"> {{ $order->customer->address }}</span>
                </div>
                <div class="my-3">
                    <span class="text-[#808080] text-[15px]">Cap: </span>
                    <span class="text-[#222222] text-[15px]"> {{ $order->customer->cap }}</span>
                </div>
                <div class="my-3">
                    <span class="text-[#808080] text-[15px]">Provincia: </span>
                    <span class="text-[#222222] text-[15px]"> {{ $order->customer->province }}</span>
                </div>
                <div class="my-3">
                    <span class="text-[#808080] text-[15px]">Città: </span>
                    <span class="text-[#222222] text-[15px]"> {{ $order->customer->city }}</span>
                </div>
            </div>
            <div x-show="activeTab === 'tab2'">
                <div class="flex">
                    <div class="w-3/5">
                        <div class="my-3">
                            <span class="text-[#808080] text-[15px]">N° Riparazione: </span>
                            <span class="text-[#222222] text-[15px]"> {{ $order->id }}</span>
                        </div>
                        <div class="my-3">
                            <span class="text-[#808080] text-[15px]">Stato riparazione: </span>
                            <span
                                class="px-2 py-1 rounded-md 
                                text-[13px] font-semibold
                                @switch($order->state)
                                    @case('Riparata')
                                        bg-[#EFF7E9] text-[#7FBC4B]
                                        @break
                                    @case('Nuova')
                                        bg-[#FFF9EC] text-[#FCC752]
                                        @break
                                    @case('In lavorazione')
                                        bg-[#E9EFF5] text-[#5E66CC]
                                        @break
                                    @case('Annullata')
                                        bg-[#FEF0F5] text-[#DC0851]
                                        @break
                                    @default
                                        bg-white text-black
                                @endswitch
                            ">
                                {{ $order->state }}
                            </span>
                        </div>
                        <div class="my-3">
                            <span class="text-[#808080] text-[15px]">Data: </span>
                            <span class="text-[#222222] text-[15px]">{{ $order->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between my-3">
                            <div>
                                <span class="text-[#808080] text-[15px]">Tecnico: </span>
                                <span class="text-[#222222] text-[15px]">{{ $order->mechanics[0]->name }}</span>
                            </div>
                            <a href="{{ route('mechanics.show', $order->mechanics[0]->pivot->mechanic_id) }}"
                                class="text-[#4453A5]">Vai
                                all'anagrafica</a>
                        </div>
                        <table class="w-full mt-6 bg-white border border-gray-200">
                            <thead class="bg-[#F5F5F5]">
                                <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                                    <th class="h-6 text-[15px] text-[#808080] font-light">Elementi</th>
                                    <th class="h-6 text-[15px] text-[#808080] font-light">N. Bolli</th>
                                    <th class="h-6 text-[15px] text-[#808080] font-light text-center">Preparazione
                                        verniciatura</th>
                                    <th class="h-6 text-[15px] text-[#808080] font-light text-center">Sostituzione</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-[#222222]">
                                @foreach ($order->carParts as $part)
                                    <tr class="text-sm text-[#222222] border-b">
                                        <td class="py-2 px-2">{{ $part->name }}</td>
                                        <td class="py-2 px-2">{{ $part->pivot->damage_count ?? 0 }}</td>
                                        <td class="py-2 px-2 text-center">
                                            <input
                                                class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                                                type="checkbox" disabled {{ $part->pivot->paint_prep ? 'checked' : '' }}>
                                        </td>
                                        <td class="py-2 px-2 text-center">
                                            <input
                                                class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                                                type="checkbox" disabled {{ $part->pivot->replacement ? 'checked' : '' }}>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="grid grid-cols-2 gap-4 my-6">
                            <div>
                                <label for="assembly_deassembly"
                                    class="block text-sm text-[#9F9F9F] text-[13px]">Montaggio/Smontaggio</label>
                                <input disabled value="{{ $order->assembly_disassembly ? 'Si' : 'No' }}" type="text"
                                    name="assembly_deassembly" id="assembly_deassembly"
                                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                                <span id="assembly_deassembly-error" class="text-red-500 text-xs hidden">Campo
                                    obbligatorio.</span>
                            </div>
                            <div class="flex place-items-center">
                                <input disabled {{ $order->aluminium ? 'checked' : '' }} type="checkbox" name="aluminium"
                                    class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"><span
                                    class="text-[15px] text-[#222222] ml-2">Alluminio</span>
                                <span id="aluminium-error" class="text-red-500 text-xs hidden">Campo
                                    obbligatorio.</span>
                            </div>
                            <div>
                                <label for="diametro" class="block text-sm text-[#9F9F9F] text-[13px]">Diametro
                                    bolli</label>
                                <input disabled type="text" value="{{ $order->damage_diameter }}" name="diametro"
                                    id="diametro"
                                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                                <span id="diametro-error" class="text-red-500 text-xs hidden">Campo
                                    obbligatorio.</span>
                            </div>
                            <div>
                                <label for="car_size" class="block text-sm text-[#9F9F9F] text-[13px]">Dimensioni
                                    veicolo</label>
                                <input disabled type="text" value="{{ $order->car_size }}" name="car_size"
                                    id="car_size"
                                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                                <span id="car_size-error" class="text-red-500 text-xs hidden">Campo
                                    obbligatorio.</span>
                            </div>
                        </div>
                    </div>

                    <div class="w-2/5 ml-4 border-l px-4 py-6">
                        <div class="flex space-x-4 justify-end mb-11">
                            <button id="openModalBtn"
                                class="px-2 flex items-center justify-center bg-[#EBF5F3] text-[#68C9BB] text-[13px] rounded-md group duration-200 hover:bg-[#68C9BB] hover:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15.559" height="20.152"
                                    viewBox="0 0 15.559 20.152" class="group-hover:fill-white">
                                    <g id="noun-pencil-3690771" transform="translate(-7.171 -0.615)">
                                        <g id="Layer_19" data-name="Layer 19" transform="translate(7.48 0.946)">
                                            <!-- Apply fill and hover effects directly to the path -->
                                            <path id="Tracciato_755" data-name="Tracciato 755"
                                                d="M1.085,16.985a.43.43,0,0,0,.617.263l3.5-1.874a.43.43,0,0,0,.192-.21L9.939,4.493h0l.83-1.948a.429.429,0,0,0-.226-.563L5.977.035a.43.43,0,0,0-.564.227L.034,12.879a.431.431,0,0,0-.018.284ZM6.035.993,9.811,2.6,9.318,3.759,5.545,2.143Zm-.827,1.94L8.981,4.55,4.659,14.687l-2.892,1.55L.884,13.078Z"
                                                transform="matrix(0.966, 0.259, -0.259, 0.966, 4.477, 0)" fill="#68C9BB"
                                                class="group-hover:fill-white transition-colors duration-200" />
                                        </g>
                                    </g>
                                </svg>
                                Modifica
                            </button>
                            <button
                                class="bg-[#F2F1FB] flex justify-center place-items-center px-2 duration-200 py-1 hover:bg-[#4453A5] text-[#4453A5] text-[13px] hover:text-white rounded-md group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="13.937" height="13.937"
                                    viewBox="0 0 13.937 13.937">
                                    <g id="Icon_feather-download" data-name="Icon feather-download"
                                        transform="translate(-3.75 -3.75)">
                                        <path id="Tracciato_630" data-name="Tracciato 630"
                                            d="M16.937,22.5v2.764a1.382,1.382,0,0,1-1.382,1.382H5.882A1.382,1.382,0,0,1,4.5,25.264V22.5"
                                            transform="translate(0 -9.709)" fill="none" stroke="#4453A5"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            class="transition-colors duration-200 group-hover:stroke-white" />
                                        <path id="Tracciato_631" data-name="Tracciato 631"
                                            d="M10.5,15l3.455,3.455L17.409,15" transform="translate(-3.236 -5.663)"
                                            fill="none" stroke="#4453A5" stroke-linecap="round"
                                            stroke-linejoin="round" stroke-width="1.5"
                                            class="transition-colors duration-200 group-hover:stroke-white" />
                                        <path id="Tracciato_632" data-name="Tracciato 632" d="M18,12.791V4.5"
                                            transform="translate(-7.282)" fill="none" stroke="#4453A5"
                                            stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            class="transition-colors duration-200 group-hover:stroke-white" />
                                    </g>
                                </svg>
                                Scarica pdf
                            </button>
                        </div>

                        <div class="relative mx-auto w-max">

                            <img class="mx-auto" src="{{ asset('images/view from side-1.svg') }}"
                                alt="car image upside down" class="w-full">


                            <div class="absolute inset-0 flex items-center justify-around">
                                {{-- parafango ant destro --}}
                                <div
                                    class="w-8 h-8 bg-[#EEEDFA] rounded-full text-[#1E1B58] flex items-center justify-center">
                                    {{ $order->carParts->where('name', 'Parafango Ant Dx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                                {{-- port ant destra --}}
                                <div class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center ml-10 mb-2">
                                    {{ $order->carParts->where('name', 'Porta Ant Dx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                                {{-- porta pos destra --}}
                                <div class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center mr-10 mb-2">
                                    {{ $order->carParts->where('name', 'Porta Pos Dx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                                <div class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center">
                                    {{ $order->carParts->where('name', 'Parafango Pos Dx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                            </div>
                            {{-- montante destro --}}
                            <div class="absolute right-16 bottom-0">
                                <div class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center">
                                    {{ $order->carParts->where('name', 'Montante Dx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                            </div>
                        </div>
                        <div class="relative mx-auto w-max">
                            <!-- Image -->
                            <img class="mx-auto my-6" src="{{ asset('images/car  top.svg') }}" alt="car image from top">

                            <!-- Circles -->
                            <div class="absolute inset-0 flex items-center justify-between w-full">
                                <div class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center ml-6">
                                    {{ $order->carParts->where('name', 'Cofano')->first()->pivot->damage_count ?? 0 }}
                                </div>
                                <div class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center ml-2">
                                    {{ $order->carParts->where('name', 'Tetto')->first()->pivot->damage_count ?? 0 }}
                                </div>
                                <div class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center mr-3">
                                    {{ $order->carParts->where('name', 'Baule')->first()->pivot->damage_count ?? 0 }}
                                </div>
                            </div>
                        </div>

                        <div class="relative mx-auto w-max">

                            <img class="mx-auto" src="{{ asset('images/view from side.svg') }}"
                                alt="car image from left">



                            <div class="absolute inset-0 mt-4 flex items-center justify-around">
                                {{-- parafango ant destro --}}
                                <div
                                    class="w-8 h-8 bg-[#EEEDFA] rounded-full text-[#1E1B58] flex items-center justify-center">
                                    {{ $order->carParts->where('name', 'Parafango Ant Sx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                                {{-- port ant destra --}}
                                <div class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center ml-10 mb-2">
                                    {{ $order->carParts->where('name', 'Porta Ant Sx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                                {{-- porta pos destra --}}
                                <div class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center mr-10 mb-2">
                                    {{ $order->carParts->where('name', 'Porta Pos Sx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                                <div class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center">
                                    {{ $order->carParts->where('name', 'Parafango Pos Sx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                            </div>
                            {{-- montante destro --}}
                            <div class="absolute right-16 top-0">
                                <div class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center">
                                    {{ $order->carParts->where('name', 'Montante Sx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                            </div>
                        </div>
                        <label class="block text-sm mt-12 text-[#9F9F9F]" for="replacements">Replacements</label>
                        <textarea disabled rows="5"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">{{ $order->replacements }}</textarea disabled>
                        <label class="block text-sm mt-4 text-[#9F9F9F]" for="notes">Appunti</label>
                        <textarea disabled rows="5"
                            class="mt-1 mb-6 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">{{ $order->notes }}</textarea>
                        <span class="text-sm font-semibold text-[#222222] text-[15px]">Prezzo :</span>
                        <span
                            class="bg-[#66C0DB] px-4 py-0.5 rounded-2xl text-white text-[15px] font-semibold">{{ $order->price }}
                            €</span>
                    </div>
                </div>

            </div>
            <div x-show="activeTab === 'tab3'" class="p-4">
                <!--  Tab 3 -->
                Tab 3
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal"
        class=" overflow-auto fixed z-50 inset-0 bg-gray-600 bg-opacity-50 justify-center items-center hidden">
        <div class="mx-auto my-20 p-6 bg-white w-[1000px] rounded-md shadow-sm relative">
            <!-- Close button -->

            <h2 class="text-lg font-semibold mb-4">Modifica commessa #{{ $order->id }}</h2>

            <!-- Form -->
            <form id="updateOrderForm" action="{{ route('orders.update', $order->id) }}" method="POST">
                @csrf
                @method('PUT')

                <span class="text-[#222222] text-[15px] mt-4">Inserire il numero di bolli presenti sulla
                    vettura.</span>
                <div class="grid grid-cols-3 gap-4 my-6">
                    @foreach ($parts as $index => $part)
                        <div>
                            <label for="part_{{ $index }}"
                                class="block text-sm text-[13px] text-[#9F9F9F]">{{ $part->name }}</label>
                            <input required type="number" min="0"
                                value="{{ $order->carParts->where('name', $part->name)->first()->pivot->damage_count ?? 0 }}"
                                name="parts[{{ $index }}][damage_count]" id="part_{{ $index }}"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                            <!-- Store only the name, not the entire object -->
                            <input type="hidden" name="parts[{{ $index }}][name]" value="{{ $part->name }}">
                            <span id="part_{{ $index }}-error" class="text-red-500 text-xs hidden"></span>
                        </div>
                    @endforeach
                    <div>
                        <label for="car_size" class="block text-sm text-[13px] text-[#9F9F9F]">Dimensioni
                            veicolo</label>
                        <select name="car_size" id="car_size"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                            <option value="">- Seleziona -</option>
                            @foreach ($car_sizes as $car_size)
                                <option value="{{ $car_size }}"
                                    {{ $car_size == $order->car_size ? 'selected' : '' }}>
                                    {{ $car_size }}
                                </option>
                            @endforeach
                        </select>
                        <span id="car_size-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>

                    <div class="flex place-items-center">
                        <input {{ $order->aluminium ? 'checked' : '' }} type="checkbox" name="aluminium"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"><span
                            class="text-[15px] text-[#222222] ml-2">Alluminio</span>
                        <span id="aluminium-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                </div>

                <label class="block text-sm text-[13px] text-[#9F9F9F]" for="replacements">Ricambi</label>
                <textarea name="replacements" id="replacements" cols="20" rows="5"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">{{ $order->replacements }}</textarea>
                <span id="replacements-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>

                <!-- Notes Textarea value="" -->
                <label class="block text-sm text-[13px] text-[#9F9F9F] mt-4" for="notes">Appunti</label>
                <textarea name="notes" id="notes" cols="10" rows="5"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">{{ $order->notes }}</textarea>
                <span id="notes-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>

                <div class="text-end h-8 mt-10">
                    <button type="button" id="closeModalBtn"
                        class="mr-4 py-2 px-4 bg-[#E8E8E8] text-[#222222] rounded-md text-sm">Annulla</button>
                    <button type="button" id="submitBtn"
                        class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm">Conferma</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openModalBtn = document.getElementById('openModalBtn');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const submitBtn = document.getElementById('submitBtn');
            const modal = document.getElementById('modal');
            const form = document.getElementById('updateOrderForm');

            // Show the modal
            if (openModalBtn) {
                openModalBtn.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                });
            }

            // Hide the modal and reset the form
            closeModalBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
                clearErrorMessages(); // Clear error messages
                // Commented out form.reset() to prevent resetting form values
                // form.reset(); 
            });

            // Handle form submission
            submitBtn.addEventListener('click', function() {
                console.log('Form submit initiated.');
                let valid = validateForm();
                if (valid) {
                    // Only submit the form if validation passes
                    form.submit(); // This will submit the form as normal
                } else {
                    console.log("Validation failed");
                    modal.classList.remove('hidden'); // Ensure modal is visible if needed
                }
            });

            // Validation function
            function validateForm() {
                let valid = true;
                clearErrorMessages(); // Clear previous error messages

                // Validate car size
                const carSize = document.getElementById('car_size').value;
                console.log('Car size:', carSize); // Log current value
                if (!carSize) {
                    showError('car_size-error', 'Campo obbligatorio.');
                    valid = false;
                }

                // Validate part inputs (damage count)
                document.querySelectorAll('input[type="number"]').forEach(input => {
                    console.log(`Damage count for ${input.id}:`, input.value); // Log current value
                    if (input.value === '' || input.value < 0) {
                        const errorId = input.id + '-error';
                        showError(errorId, 'Il numero deve essere maggiore o uguale a 0.');
                        valid = false;
                    }
                });

                // Validate replacements textarea
                const replacements = document.getElementById('replacements');
                if (!replacements.value.trim()) {
                    showError('replacements-error', 'Campo obbligatorio.');
                    valid = false;
                }

                // Validate notes textarea
                const notes = document.getElementById('notes');
                console.log('Notes textarea:', notes.value); // Log current value
                if (!notes.value.trim()) {
                    showError('notes-error', 'Campo obbligatorio.');
                    valid = false;
                }

                return valid; // If no validation errors, return true
            }

            // Show error messages
            function showError(id, message) {
                const errorElement = document.getElementById(id);
                if (errorElement) {
                    errorElement.textContent = message;
                    errorElement.classList.remove('hidden');
                }
            }

            // Clear error messages
            function clearErrorMessages() {
                document.querySelectorAll('.text-red-500').forEach(errorElement => {
                    errorElement.textContent = '';
                    errorElement.classList.add('hidden');
                });
            }
        });
    </script>
@endsection
