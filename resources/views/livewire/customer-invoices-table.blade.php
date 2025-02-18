<div>
    <div class="bg-white">
        <div class="mb-4 h-8">
            <input type="text" class="border border-gray-300 rounded mr-6 h-8 w-[600px]" placeholder="Cerca elemento..."
                wire:model.debounce.300ms.live="searchTerm" />
        </div>
    </div>
    <div class="overflow-x-auto rounded-md">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-[#F5F5F5]">
                <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Data</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N. Fattura</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Stato</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Imponibile</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">IVA</th>
                    <th class="py-3 px-6"></th>
                </tr>
            </thead>
            <tbody class="text-sm text-[#222222]">
                @forelse($rows as $row)
                    <tr translate="no" class="border-b border-gray-200 hover:bg-gray-100">

                        <td class="py-3 px-6">{{ $row->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-6">{{ $row->number }}</td>
                        <td class="py-3 px-6 relative">
                            <div x-data="{ open: false }" class="relative">
                                <!-- Dropdown Button -->
                                <button @click="open = !open"
                                    class="block w-full text-left rounded {{ $row->is_closed ? 'bg-[#F5F5F5] text-[#9F9F9F]' : 'bg-[#EFF7E9] text-[#7FBC4B]' }} text-[13px] font-semibold py-1 px-2 flex items-center justify-between">
                                    {{ $row->is_closed ? 'Chiuso' : 'Aperto' }}
                                    <!-- Chevron Icon -->
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <!-- Dropdown Menu -->
                                <div x-show="open" @click.away="open = false"
                                    class="absolute z-10 w-full mt-1 bg-white rounded shadow-md">
                                    @if ($row->is_closed)
                                        <div @click="open = false"
                                            wire:click="updateIsClosed({{ $row->id }}, false)"
                                            class="bg-[#EFF7E9] text-[#7FBC4B] px-3 py-2 cursor-pointer text-[13px] font-semibold">
                                            Aperto
                                        </div>
                                    @else
                                        <div @click="open = false"
                                            wire:click="updateIsClosed({{ $row->id }}, true)"
                                            class="bg-[#F5F5F5] text-[#9F9F9F] px-3 py-2 cursor-pointer text-[13px] font-semibold">
                                            Chiuso
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-6">{{ $row->price }}€</td>
                        <td class="py-3 px-6"> {{ $row->iva }}€</td>
                        <td class="py-3 px-6 flex space-x-2">
                            @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\Invoice::class], key(str()->random(10)))
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
