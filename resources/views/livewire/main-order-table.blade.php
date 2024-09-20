<div class="mt-4">
    <h3 class="text-[#222222] text-2xl font-semibold mb-4">Lista riparazioni</h3>
    <div class="bg-white p-4">

        <div class="mb-4 flex justify-between h-8">
            <input type="text" class="p-2 border border-gray-300 rounded" placeholder="Search..."
                wire:model.debounce.300ms.live="searchTerm" />
            <div class="flex">
                @livewire('date-filter')
                <a href="{{ route('orders.create') }}">

                    <button class="ml-4 py-1 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-full">+ Crea nuova
                        riparazione</button>
                </a>
            </div>
        </div>
        <span>
            @foreach ($selectedRows as $x)
                {{ $x }}
            @endforeach
        </span>
        <div class="overflow-x-auto rounded-md">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-[#F5F5F5]">
                    <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">
                            <input type="checkbox"
                                class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                                wire:model.live="selectAll" wire:key="{{ str()->random(10) }}">
                        </th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N. Riparazione</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Cliente</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Responsabile</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Stato Riparazione</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Targa/Telaio</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Tecnico</th>

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
                            <td class="py-3 px-6">{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3 px-6">{{ $row->customer->name }}</td>
                            <td class="py-3 px-6">
                                {{ $row->customerInfo ? $row->customerInfo->admin_name : 'N/A' }}
                            </td>
                            <td class="py-3 px-6 relative" wire:key="order-{{ $row->id }}">
                                <div x-data="{ open: false }" class="relative">
                                    <!-- Dropdown Button -->
                                    <button @click="open = !open"
                                        class="block w-full text-left rounded {{ $states[$row->state] }} {{ $statesText[$row->state] }} text-[13px] font-semibold py-1 px-2 flex items-center justify-between">
                                        {{ ucfirst($row->state) }}
                                        <svg class="w-4 h-4 ml-2 {{ $statesText[$row->state] }}" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div x-show="open" @click.away="open = false"
                                        class="absolute z-10 w-full mt-1 bg-white rounded shadow-md">
                                        @foreach ($states as $state => $color)
                                            @if ($state !== $row->state)
                                                <div @click="open = false"
                                                    wire:click="updateState({{ $row->id }}, '{{ $state }}')"
                                                    class="{{ $color }} {{ $statesText[$state] }} px-3 py-2 cursor-pointer text-[13px] font-semibold">
                                                    {{ ucfirst($state) }}
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </td>

                            <td class="py-3 px-6">{{ $row->plate }}</td>
                            <td class="py-3 px-6 relative group">
                                @if ($row->mechanics->isNotEmpty())
                                    <div class="flex -space-x-4">
                                        @foreach ($row->mechanics as $mechanic)
                                            <span
                                                class="inline-block w-8 h-8 bg-red-300 rounded-full border border-black">
                                            </span>
                                        @endforeach
                                    </div>
                                    <div
                                        class="absolute right-0 top-4 mt-1 hidden group-hover:block bg-gray-700 text-white text-xs rounded-lg px-2 py-1 whitespace-nowrap z-10">
                                        @foreach ($row->mechanics as $mechanic)
                                            {{ $mechanic->name }}<br>
                                        @endforeach
                                    </div>
                                @else
                                    N/A
                                @endif
                            </td>


                            <td class="py-3 px-6 flex space-x-2">
                                @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\Order::class], key(str()->random(10)))
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
                                    Scarica pdf
                                </button>

                                <button
                                    class="bg-[#F2F1FB] flex justify-center place-items-center px-2 py-1 duration-200 hover:bg-[#4453A5] text-[#4453A5] text-[13px] hover:text-white rounded-md group">
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
                                    Scarica foto
                                </button>
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
        'modelClass' => App\Models\Order::class,
        'modelId' => $selectedRows,
        'buttons' => ['edit', 'delete', 'download'],
    ])


    @if ($showModal)
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
    @endif

</div>
