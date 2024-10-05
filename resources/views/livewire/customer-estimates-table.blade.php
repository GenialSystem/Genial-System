<div>
    <div class="bg-white">
        <div class="flex space-x-4">
            <input type="text" class="p-2 border border-gray-300 rounded h-8 w-[400px]" placeholder="Cerca elemento..."
                wire:model.debounce.300ms.live="searchTerm" />

            <select class="border border-gray-300 rounded pl-2 pr-20 h-8 leading-none" wire:model.live="selectedState">
                <option value="">Tutti</option>
                @foreach ($states as $state => $color)
                    <option value="{{ $state }}">{{ ucfirst($state) }}</option>
                @endforeach
            </select>

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
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Numero</th>
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
                            <td class="py-3 px-6">{{ $row->number }}</td>
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
</div>
