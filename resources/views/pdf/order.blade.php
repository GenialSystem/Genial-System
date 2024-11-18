<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css'])

</head>

<body class="font-tome antialiased bg-[#F5F5F5] p-4">
    <img src="{{ asset('images/logo.svg') }}" alt="logo" class="w-40 h-40 mx-auto">

    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">Dettaglio riparazione #{{ $order->id }}</h4>

    <div class="2xl:flex 2xl:space-x-4 space-y-4 2xl:space-y-0">
        <div class="flex gap-4 2xl:block 2xl:w-1/3 2xl:space-y-4">
            <div class="bg-white p-4 rounded-sm w-full">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Info generali riparazioni</span>
                </div>

                <div class="px-2 space-y-4 test">
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
                    <div class="flex justify-between">
                        <div>
                            <span class="text-[#808080] text-[15px]">Tecnico: </span>
                            <span class="text-[#222222] text-[15px]">{{ $order->mechanics[0]->name }}
                                {{ $order->mechanics[0]->surname }}</span>
                        </div>
                        <a href="{{ route('mechanics.show', App\Models\MechanicInfo::where('user_id', $order->mechanics[0]->pivot->mechanic_id)->value('id')) }}"
                            class="text-[#4453A5]">Vai
                            all'anagrafica</a>
                    </div>
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
                        <span class="text-[#808080] text-[15px]">Importo: </span>
                        <span class="text-[#222222] text-[15px]"> {{ $order->price }}€</span>
                    </div>
                    <div>
                        <span class="text-[#808080] text-[15px]">Stato pagamento: </span>
                        <span
                            class="px-2 py-1 rounded-md 
                            text-[13px] font-semibold bg-[#EFF7E9] text-[#7FBC4B]">
                            Saldato
                        </span>
                    </div>
                    @role('admin')
                        <hr class="my-3">
                        <div>
                            <span class="text-[#808080] text-[15px]">Tecnico: </span>
                            <span class="text-[#222222] text-[15px]"> {{ $order->mechanics[0]->name }}
                                {{ $order->mechanics[0]->surname }}</span>
                        </div>
                        <div>
                            <span class="text-[#808080] text-[15px]">Guadagno tecnico: </span>
                            <span class="text-[#222222] text-[15px]">
                                {{ number_format(($order->earn_mechanic_percentage / 100) * floatval(str_replace(['.', ','], ['', '.'], $order->price)), 2, ',', '.') }}€
                            </span>
                        </div>
                    @endrole
                </div>
            </div>
        </div>
        <div class="2xl:w-2/3 bg-white p-4 mb-10 2xl:mb-0">


            <div>
                <div class="xl:flex">
                    <div class="xl:w-3/5">
                        @livewire('car-parts-table', ['order' => $order])
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
                                <input disabled {{ $order->aluminium ? 'checked' : '' }} type="checkbox"
                                    name="aluminium"
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

                    <div class="xl:w-2/5 xl:ml-4 xl:border-l px-4 py-6">


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
                                <div
                                    class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center ml-10 mb-2">
                                    {{ $order->carParts->where('name', 'Porta Ant Dx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                                {{-- porta pos destra --}}
                                <div
                                    class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center mr-10 mb-2">
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
                                <div
                                    class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center ml-10 mb-2">
                                    {{ $order->carParts->where('name', 'Porta Ant Sx')->first()->pivot->damage_count ?? 0 }}
                                </div>
                                {{-- porta pos destra --}}
                                <div
                                    class="w-8 h-8 bg-[#EEEDFA] rounded-full flex items-center justify-center mr-10 mb-2">
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
            <div class="p-4">
                <span class="text-sm font-semibold text-[#222222] text-[15px]">Foto</span>
                <div class="grid grid-cols-2 2xl:grid-cols-4 gap-6">
                    @forelse ($order->images->where('disassembly', 0) as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Order Image"
                            class="h-[315px] object-cover rounded-md">
                    @empty
                        <span>Nessuna foto presente</span>
                    @endforelse

                </div>
            </div>
            <div class="p-4">
                <span class="text-sm font-semibold text-[#222222] text-[15px]">Foto smontaggio</span>
                <div class="grid grid-cols-4 gap-6">
                    @forelse ($order->images->where('disassembly', 1) as $image)
                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Order Image"
                            class="h-[315px] object-cover rounded-md">
                    @empty
                        <span>Nessuna foto presente</span>
                    @endforelse

                </div>
            </div>

        </div>
    </div>

</body>

</html>
