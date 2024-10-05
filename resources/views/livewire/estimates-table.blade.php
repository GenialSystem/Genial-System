<div class="mt-4">
    <h3 class="text-[#222222] text-2xl font-semibold mb-4">Preventivi</h3>
    <div class="bg-white p-4">

        <div class="h-8 flex justify-between">
            <div class="flex space-x-4">
                <input type="text" class="p-2 border border-gray-300 rounded h-8 w-[600px]"
                    placeholder="Cerca elemento..." wire:model.debounce.300ms.live="searchTerm" />

                <select class="border border-gray-300 rounded pl-2 pr-20 h-8 leading-none"
                    wire:model.live="selectedState">
                    <option value="">Tutti</option>
                    @foreach ($states as $state => $color)
                        <option value="{{ $state }}">{{ ucfirst($state) }}</option>
                    @endforeach
                </select>

            </div>

            <button wire:click="$dispatch('openModal', { component: 'estimate-modal' })"
                class="px-2 bg-[#1E1B58] text-white rounded-md text-sm h-8">+
                Crea
                nuovo preventivo</button>
        </div>

        {{--
        bg-[#EFF7E9]
        bg-[#FFF9EC]
        bg-[#E9EFF5]
        bg-[#FEF0F5]
        bg-[#EBF5F3]

        text-[#7FBC4B]
        text-[#FCC752]
        text-[#E57A7A]
        text-[#DC0851]
        text-[#68C9BB]
        bg-[#FCEEEE]
        bg-[#7AA3E5],
        bg-[#A892D1],
        bg-[#E68B69],
        --}}
        {{-- @dd($rows[0]->customer) --}}
        <div class="overflow-x-auto rounded-md mt-4">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-[#F5F5F5]">
                    <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">
                            <input type="checkbox"
                                class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                                wire:model.live="selectAll" wire:key="{{ str()->random(10) }}">
                        </th>
                        <th class="py-3
                                px-6 text-[15px] text-[#808080] font-light">
                            Numero
                        </th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Cliente</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Responsabile</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Tipologia Lavorazione</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Importo</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Stato</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Data</th>
                        <th class="py-3 px-6"></th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#222222]">
                    @forelse($rows as $row)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">
                                <input id="{{ rand() }}" type="checkbox"
                                    class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                                    wire:click="toggleRow('{{ $row->id }}')"
                                    @if (in_array((string) $row->id, $selectedRows)) checked @endif>
                            </td>
                            <td class="py-3 px-6">{{ $row->number }}</td>
                            <td class="py-3 px-6">{{ $row->customer->user->name ?? 'N/A' }}
                                {{ $row->customer->user->surname }}</td>
                            <td class="py-3 px-6">
                                {{ $row->customer ? $row->customer->admin_name : 'N/A' }}
                            </td>
                            <td class="py-3 px-6 text-white">
                                <div class="inline rounded-md py-1 px-2 text-center {{ $typeColor[$row->type] }}">
                                    {{ $row->type }}
                                </div>
                            </td>
                            <td class="py-3 px-6">{{ $row->price }}€</td>
                            <td class="py-3 px-6 relative">
                                <div x-data="{ open: false }" class="relative">
                                    <!-- Dropdown Button -->
                                    <button @click="open = !open"
                                        class="block w-full text-left rounded {{ $states[$row->state] }} {{ $statesText[$row->state] }} text-[13px] font-semibold py-1 px-2 flex items-center justify-between">
                                        {{ ucfirst($row->state) }}
                                        <!-- Chevron Icon -->
                                        <svg class="w-4 h-4 ml-2 {{ $statesText[$row->state] }}" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    <!-- Dropdown Menu -->
                                    <div x-show="open" @click.away="open = false"
                                        class="absolute w-full mt-1 bg-white rounded shadow-md z-50">
                                        @foreach ($states as $state => $color)
                                            @if ($state !== $row->state)
                                                <div wire:key="{{ str()->random(10) }}" @click="open = false"
                                                    wire:click="updateState({{ $row->id }}, '{{ $state }}')"
                                                    class="{{ $color }} {{ $statesText[$state] }} px-3 py-2 cursor-pointer text-[13px] font-semibold">
                                                    {{ ucfirst($state) }}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </td>


                            <td class="py-3 px-6">{{ $row->created_at->format('d M Y') }}</td>

                            <td class="py-3 px-6 flex space-x-2">
                                @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\Estimate::class], key(str()->random(10)))
                                <div
                                    wire:click="$dispatch('openModal', { component: 'estimate-modal', arguments: { estimate: {{ $row }} }})">
                                    <div
                                        class="bg-[#EDF8FB] w-6 p-1 flex items-center justify-center group hover:bg-[#66C0DB] duration-200 rounded-sm">
                                        <button class="flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15.559" height="20.152"
                                                viewBox="0 0 15.559 20.152" class="group-hover:fill-white">
                                                <g id="noun-pencil-3690771" transform="translate(-7.171 -0.615)">
                                                    <g id="Layer_19" data-name="Layer 19"
                                                        transform="translate(7.48 0.946)">
                                                        <!-- Apply fill and hover effects directly to the path -->
                                                        <path id="Tracciato_755" data-name="Tracciato 755"
                                                            d="M1.085,16.985a.43.43,0,0,0,.617.263l3.5-1.874a.43.43,0,0,0,.192-.21L9.939,4.493h0l.83-1.948a.429.429,0,0,0-.226-.563L5.977.035a.43.43,0,0,0-.564.227L.034,12.879a.431.431,0,0,0-.018.284ZM6.035.993,9.811,2.6,9.318,3.759,5.545,2.143Zm-.827,1.94L8.981,4.55,4.659,14.687l-2.892,1.55L.884,13.078Z"
                                                            transform="matrix(0.966, 0.259, -0.259, 0.966, 4.477, 0)"
                                                            fill="#66c0db"
                                                            class="group-hover:fill-white transition-colors duration-200" />
                                                    </g>
                                                </g>
                                            </svg>
                                        </button>
                                    </div>

                                </div>
                                @livewire('download-button', key(str()->random(10)))
                                @livewire('delete-button', ['modelId' => $row->id, 'modelName' => 'estimates', 'modelClass' => \App\Models\Estimate::class], key(str()->random(10)))
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-3 px-6 text-center">Nessun risultato</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $rows->links() }}
            </div>
        </div>
    </div>
    @livewire('selection-banner', [
        'modelClass' => App\Models\Estimate::class,
        'modelId' => $selectedRows,
        'buttons' => ['delete', 'download'],
    ])
    {{-- @if ($showModal)
        <div class="fixed inset-0 bg-[#707070] bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-md w-[600px]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-[#222222] mb-0">
                        {{ count($selectedRows) }} Elementi selezionati
                    </h3>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-[#9F9F9F] text-3xl">
                        &times;
                    </button>
                </div>


                <span class="text-[#222222] text-[15px]">Modifica lo stato degli elementi selezionati</span>
                <form wire:submit.prevent="applyStateToSelectedRows" class="mt-5">
                    <div class="mb-4">
                        <label for="state" class="block text-[#9F9F9F] text-[13px]">Stato riparazione</label>
                        <select id="state" wire:model="newState"
                            class="mt-2 p-2 border border-gray-300 rounded w-full">
                            <option value="">- Seleziona -</option>
                            @foreach ($states as $state => $color)
                                <option value="{{ $state }}">{{ ucfirst($state) }}</option>
                            @endforeach
                        </select>
                        @error('newState')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex justify-end">

                        <button wire:click="showModal"
                            class="mt-3 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-8">
                            Conferma
                        </button>

                    </div>
                </form>
            </div>
        </div>
    @endif --}}
</div>
