@extends('layout')

@section('content')
    @livewire('back-button')
    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">Dettaglio riparazione #{{ $order->id }}</h4>

    <div class="2xl:flex 2xl:space-x-4 space-y-4 2xl:space-y-0">
        <div class="flex gap-4 2xl:block 2xl:w-1/3 2xl:space-y-4">
            <div class="bg-white p-4 rounded-sm w-full">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Info generali riparazioni</span>
                </div>

                <div class="px-2 space-y-4">
                    <div>
                        <span class="text-[#808080] text-[15px]">N. Riparazione: </span>
                        <span class="text-[#222222] text-[15px]">{{ $order->id }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 text-[15px] mr-2">Stato riparazione: </span>
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
                    <div>
                        <span class="text-[#808080] text-[15px]">Colore: </span>
                        <span class="text-[#222222] text-[15px]">{{ $order->color }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Data: </span>
                        <span class="text-[#222222] text-[15px]">{{ $order->created_at->format('d/m/Y') }}</span>
                    </div>
                    @hasanyrole(['admin', 'mechanic'])
                        <div class="flex justify-between">
                            <div>
                                <span class="text-[#808080] text-[15px]">Cliente: </span>
                                <span class="text-[#222222] text-[15px]">{{ $order->customer->user->name }}
                                    {{ $order->customer->user->surname }}</span>
                            </div>
                            <a href="{{ route('customers.show', $order->customer->id) }}" class="text-[#4453A5]">Vai
                                all'anagrafica</a>
                        </div>
                        <div>
                            <span class="text-[#808080] text-[15px]">Responsabile: </span>
                            <span class="text-[#222222] text-[15px]">{{ $order->customer->admin_name }}</span>
                        </div>
                    @endhasanyrole
                    @if (!empty($order->mechanics) && isset($order->mechanics[0]->user))
                        <div>
                            <span class="text-[#808080] text-[15px]">Tecnici: </span>
                            <span class="text-[#222222] text-[15px]">
                                {{ $order->mechanics->map(fn($mechanic) => $mechanic->user->name . ' ' . $mechanic->user->surname)->implode(', ') }}
                            </span>
                        </div>
                    @else
                        <span class="text-[#808080] text-[15px]">Tecnico: </span>
                        <span class="text-[#222222] text-[15px]">Nessun tecnico assegnato</span>
                    @endif

                    <hr class="my-3">
                    <div>
                        <span class="text-[#808080] text-[15px]">Città: </span>
                        <span class="text-[#222222] text-[15px]">
                            {{ $order->customer->user->city }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Targa/Telaio: </span>
                        <span class="text-[#222222] text-[15px]">{{ $order->plate }}</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Marca/Modello: </span>
                        <span class="text-[#222222] text-[15px]">{{ $order->brand }}</span>
                    </div>
                </div>
            </div>
            <div class="bg-white p-4 rounded-sm w-full">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Dettagli pagamento</span>
                </div>
                <div class="px-2 space-y-4">
                    <div>
                        <span class="text-[#808080] text-[15px]">Importo + IVA: </span>
                        <span class="text-[#222222] text-[15px]"> {{ $order->price }}€</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Stato pagamento: </span>
                        <span class="text-[#222222] text-[15px]"> {{ $order->payment }}</span>

                    </div>
                    @role('admin')
                        <hr class="my-3">
                        <div>
                            @if (!empty($order->mechanics) && isset($order->mechanics[0]->user))
                                <span class="text-[#808080] text-[15px]">Tecnico: </span>
                                <span class="text-[#222222] text-[15px]">
                                    {{ $order->mechanics[0]->user->name }} {{ $order->mechanics[0]->user->surname }}
                                </span>
                            @else
                                <span class="text-[#808080] text-[15px]">Tecnico: </span>
                                <span class="text-[#222222] text-[15px]">Nessun tecnico assegnato</span>
                            @endif

                        </div>
                        <div>
                            <span class="text-[#808080] text-[15px]">Guadagno singolo tecnico: </span>
                            <span class="text-[#222222] text-[15px]">
                                {{ number_format((($order->earn_mechanic_percentage / count($order->mechanics) )/ 100) * floatval(str_replace(['.', ','], ['', '.'], $order->price)), 2, ',', '.') }}€
                            </span>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
        <div x-data="{ activeTab: 'tab1' }" class="2xl:w-2/3 bg-white p-4 mb-10 2xl:mb-0">
            <div class="flex space-x-4 border-b">
                <button @click="activeTab = 'tab1'"
                    :class="activeTab === 'tab1' ? 'border-[#4453A5] text-[#4453A5]' : 'border-transparent text-[#9F9F9F]'"
                    class="relative py-2 px-4 border-b-2 focus:outline-none">
                    <span class="inline-block pr-2">
                        Scheda tecnica
                    </span>
                </button>

                <button @click="activeTab = 'tab2'"
                    :class="activeTab === 'tab2' ? 'border-[#4453A5] text-[#4453A5]' : 'border-transparent text-[#9F9F9F]'"
                    class="py-2 px-4 border-b-2 focus:outline-none">
                    Foto
                </button>
                <button @click="activeTab = 'tab3'"
                    :class="activeTab === 'tab3' ? 'border-[#4453A5] text-[#4453A5]' : 'border-transparent text-[#9F9F9F]'"
                    class="py-2 px-4 border-b-2 focus:outline-none">
                    Foto Smontaggio
                </button>
                @hasanyrole(['admin', 'customer'])
                    <button @click="activeTab = 'tab4'"
                        :class="activeTab === 'tab4' ? 'border-[#4453A5] text-[#4453A5]' :
                            'border-transparent text-[#9F9F9F]'"
                        class="py-2 px-4 border-b-2 focus:outline-none">
                        Documenti
                    </button>
                @endhasanyrole
            </div>

            <div x-show="activeTab === 'tab1'">
                <div class="xl:flex">
                    <div class="xl:w-3/5">
                        @livewire('car-parts-table', ['order' => $order])
                        <div class="grid grid-cols-2 gap-4 my-6">
                            <div>
                                <label for="assembly_disassembly"
                                    class="block text-sm text-[#9F9F9F] text-[13px]">Montaggio/Smontaggio</label>
                                <select disabled name="assembly_disassembly" id="assembly_disassembly" name="status"
                                    class="mt-1 p-2 border-transparent rounded-md w-20 bg-white">
                                    <option value="1" <?= $order->assembly_disassembly ? 'selected' : '' ?>>Si</option>
                                    <option value="0" <?= !$order->assembly_disassembly ? 'selected' : '' ?>>No
                                    </option>
                                </select>

                                {{-- <input disabled value="{{ $order->assembly_disassembly ? 'checked' : '' }}" type="checkbox"
                                    name="assembly_disassembly" id="assembly_disassembly"
                                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"> --}}

                                <span id="assembly_disassembly-error" class="text-red-500 text-xs hidden">Campo
                                    obbligatorio.</span>




                            </div>
                            <div class="flex place-items-center">
                                <input disabled {{ $order->aluminium ? 'checked' : '' }} type="checkbox" name="aluminium"
                                    class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"><span
                                    class="text-[15px] text-[#222222] ml-2">Alluminio</span>
                                <span id="aluminium-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
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

                                <input disabled type="text" value="{{ __('orders.' . $order->car_size) }}"
                                    name="car_size" id="car_size"
                                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                                <span id="car_size-error" class="text-red-500 text-xs hidden">Campo
                                    obbligatorio.</span>
                            </div>
                            <div>
                                <label for="color" class="block text-sm text-[#9F9F9F] text-[13px]">Colore</label>
                                <input disabled type="text" value="{{ $order->color }}" name="color"
                                    id="color"
                                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                                <span id="color-error" class="text-red-500 text-xs hidden">Campo
                                    obbligatorio.</span>
                            </div>
                        </div>
                    </div>

                    <div class="xl:w-2/5 xl:ml-4 xl:border-l px-4 py-6">
                        <div class="flex space-x-4 justify-end mb-11">
                            @hasanyrole(['admin', 'mechanic'])
                                <button
                                    onclick="Livewire.dispatch('openModal', { component: 'order-edit-modal', arguments:{'order':{{ $order }}} })"
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
                            @endhasanyrole
                            <a href="{{ route('downloadPDF', ['model' => 'order', 'ids' => $order->id]) }}">

                                <button
                                    class="bg-[#F2F1FB] flex justify-center place-items-center px-2 duration-200 py-1 hover:bg-[#4453A5] text-[#4453A5] text-[13px] hover:text-white rounded-md group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="13.937"
                                        height="13.937" viewBox="0 0 13.937 13.937">
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
                                    {{ __('orders.Scarica pdf') }}
                                </button>
                            </a>
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
                            <img class="mx-auto my-6 object-cover" src="{{ asset('images/car  top.svg') }}"
                                alt="car image from top">

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
                        <span class="text-sm font-semibold text-[#222222] text-[15px]">Prezzo +IVA:</span>
                        <span
                            class="bg-[#66C0DB] px-4 py-0.5 rounded-2xl text-white text-[15px] font-semibold">{{ $order->price }}
                            €</span>
                    </div>
                </div>

            </div>
            <div x-show="activeTab === 'tab2'" class="p-4">
                <div class="mb-4 justify-end flex">
                    @hasanyrole(['admin', 'mechanic'])
                        <button
                            onclick="Livewire.dispatch('openModal', { component: 'order-upload-modal', arguments : {order:{{ $order }}, type : 'normal'} })"
                            class="px-2 flex items-center justify-center bg-[#EBF5F3] text-[#68C9BB] text-[13px] rounded-md group duration-200 hover:bg-[#68C9BB] hover:text-white mr-3">
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
                        @livewire('download-order-photos', ['orderId' => $order->id])
                    @endhasanyrole
                </div>
                <div class="grid grid-cols-2 2xl:grid-cols-4 gap-6">
                    @forelse ($order->images->where('disassembly', 0) as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Order Image"
                            class="h-[315px] object-cover rounded-md">
                    @empty
                        <span>Nessuna foto presente</span>
                    @endforelse

                </div>
            </div>
            <div x-show="activeTab === 'tab3'" class="p-4">
                <div class="mb-4 justify-end flex">
                    @hasanyrole(['admin', 'mechanic'])
                        <button
                            onclick="Livewire.dispatch('openModal', { component: 'order-upload-modal', arguments : {order:{{ $order }}, type : 'disassembly'} })"
                            class="px-2 flex items-center justify-center bg-[#EBF5F3] text-[#68C9BB] text-[13px] rounded-md group duration-200 hover:bg-[#68C9BB] hover:text-white mr-3">
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
                        @livewire('download-order-photos', ['orderId' => $order->id])
                    @endhasanyrole
                </div>
                <div class="grid grid-cols-4 gap-6">
                    @forelse ($order->images->where('disassembly', 1) as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Order Image"
                            class="h-[315px] object-cover rounded-md">
                    @empty
                        <span>Nessuna foto presente</span>
                    @endforelse

                </div>
            </div>
            <div x-show="activeTab === 'tab4'" class="p-4">
                @livewire('general-docs', ['docs' => $order->files, 'modelId' => $order->id, 'isOrderModel' => 1])
            </div>
        </div>
    </div>
@endsection
