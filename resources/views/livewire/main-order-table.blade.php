<div class="mt-4">
    <h3 class="text-[#222222] text-2xl font-semibold mb-4">Lista riparazioni</h3>
    <div class="bg-white p-4">
        <div class="mb-4 space-y-3 2xl:space-y-0 2xl:flex justify-between">
            <input type="text" class="p-2 border border-gray-300 rounded w-full xl:w-[600px] h-8"
                placeholder="Cerca elemento..." wire:model.debounce.300ms.live="searchTerm" />
            <div class="flex justify-between">
                @livewire('date-filter')
                <!-- Mechanic Select -->
                @role('customer')
                    <select wire:model.live="selectedMechanic"
                        class="border border-gray-300 rounded pl-2 pr-20 h-8 leading-none text-[#9F9F9F] text-sm ml-4">
                        <option value="">Tecnico</option>
                        @foreach ($mechanics as $mechanic)
                            <option value="{{ $mechanic->user->id }}">{{ $mechanic->user->name }}
                                {{ $mechanic->user->surname }}</option>
                        @endforeach
                    </select>
                @endrole
                @role('admin')
                    <a href="{{ route('orders.create') }}">
                        <button class="ml-4 py-1 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-8">+ Crea nuova
                            riparazione</button>
                    </a>
                @endrole
            </div>
        </div>
    <x-top-scrollbar id="top-scrollbar" />

        <div id="table" class="overflow-x-auto rounded-md">
            <table id="get-width"  class="min-w-full bg-white border border-gray-200 whitespace-nowrap">
                <thead class="bg-[#F5F5F5]">
                    <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                        @role('customer')
                            <th class="py-3 px-6 text-[15px] text-[#808080] font-light">
                                Data
                            </th>
                        @endrole
                        @role('admin')
                            <th class="py-3 px-6 text-[15px] text-[#808080] font-light">
                                <input type="checkbox"
                                    class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                                    wire:model.live="selectAll" wire:key="{{ str()->random(10) }}">
                            </th>
                            <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N. Riparazione</th>
                            <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Cliente</th>
                            <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Responsabile</th>
                        @endrole
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Stato Riparazione</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Colore</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Tecnico</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Targa/Telaio</th>

                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Importo</th>

                        <th class="py-3 px-6"></th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#222222]">
                    @forelse($rows as $row)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            @role('customer')
                                <td class="py-3 px-6">{{ $row->created_at->format('d M Y') }}</td>
                            @endrole
                            @role('admin')
                                <td wire:key="{{ str()->random(10) }}" class="py-3 px-6">
                                    <input type="checkbox"
                                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                                        wire:click="toggleRow('{{ $row->id }}')"
                                        @if (in_array((string) $row->id, $selectedRows)) checked @endif>
                                </td>
                                <td class="py-3 px-6">#{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3 px-6">
                                    {{ $row->customer->user->name . ' ' . $row->customer->user->surname ?? 'N/A' }}</td>
                                <td class="py-3 px-6">
                                    {{ $row->customer ? $row->customer->admin_name : 'N/A' }}
                                </td>
                            @endrole
                            <td class="py-3 px-6 relative" wire:key="order-{{ $row->id }}">
                                <div x-data="{ open: false }" class="relative">
                                    <!-- Dropdown Button -->
                                    <button @if (Auth::user()->hasAnyRole(['admin', 'mechanic'])) @click="open = !open" @endif
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
                            <td class="py-3 px-6 relative group">
                                {{ $row->color }}
                            </td>
                            <td class="py-3 px-6 relative group">
                                @if ($row->mechanics->isNotEmpty())
                                    <div class="flex -space-x-4">
                                        @foreach ($row->mechanics->take(4) as $mechanic)
                                            <img class="inline-block w-8 h-8 rounded-full"
                                                src="{{ asset($mechanic->image_path ?? 'images/placeholder.png') }}"
                                                alt="profile image">
                                        @endforeach
                                    </div>

                                    <!-- Tooltip that shows all mechanics on hover -->
                                    <div
                                        class="absolute right-0 top-4 mt-1 hidden group-hover:block bg-gray-700 text-white text-xs rounded-lg px-2 py-1 whitespace-nowrap z-10">
                                        @foreach ($row->mechanics as $mechanic)
                                            {{ $mechanic->user->name }} {{ $mechanic->user->surname }}<br>
                                        @endforeach
                                    </div>
                                @else
                                    N/A
                                @endif
                            <td class="py-3 px-6">{{ $row->plate }}</td>
                            <td class="py-3 px-6">{{ $row->price }}€</td>
                            <td class="py-3 px-6 flex space-x-2">
                                @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\Order::class], key(str()->random(10)))
                                @role('admin')
                                    <a href="{{ route('downloadPDF', ['model' => 'order', 'ids' => $row->id]) }}">
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
                                                        d="M10.5,15l3.455,3.455L17.409,15"
                                                        transform="translate(-3.236 -5.663)" fill="none" stroke="#4453A5"
                                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        class="transition-colors duration-200 group-hover:stroke-white" />
                                                    <path id="Tracciato_632" data-name="Tracciato 632" d="M18,12.791V4.5"
                                                        transform="translate(-7.282)" fill="none" stroke="#4453A5"
                                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                        class="transition-colors duration-200 group-hover:stroke-white" />
                                                </g>
                                            </svg>
                                            Scarica pdf
                                        </button>
                                    </a>

                                    @livewire('download-order-photos', ['orderId' => $row->id], key(str()->random(10)))
                                @endrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-3 px-6 text-center">Nessun risultato</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="my-4">
                {{ $rows->links() }}
            </div>
        </div>
    </div>

    @livewire('selection-banner', [
        'modelClass' => App\Models\Order::class,
        'modelId' => $selectedRows,
        'modelName' => 'order',
        'buttons' => ['edit', 'delete', 'download'],
    ])

</div>

{{-- script per sincronia tra scrollbar superiore e inferiore (la scrollbar inferiore è quella legit del browser) --}}
<script src="{{ asset('js/top-scrollbar.js') }}"></script>
