<div class="mt-4 bg-white p-4">
    <h3 class="text-[#222222] text-lg font-semibold mb-4">Lista riparazioni</h3>
    <div id="orders-table" class="overflow-x-auto overflow-y-scroll rounded-md">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-[#F5F5F5]">
                <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                    <!-- Table Headers -->
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N. Riparazione</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Data</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Stato Riparazione</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Tecnico</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Cliente</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Responsabile</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Targa/Telaio</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Importo</th>
                    <th class="py-3 px-6"></th>
                </tr>
            </thead>
            <tbody class="text-sm text-[#222222]">
                @forelse($rows as $row)
                    <tr :key="{{ $row->id }}" class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-3 px-6">{{ $row->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-6 relative" wire:key="order-{{ $row->id }}">
                            <div x-data="{ open: false }" class="relative">
                                <!-- Dropdown Button -->
                                <button @click="open = !open"
                                    class="block w-full text-left rounded {{ $states[$row->state] }} {{ $statesText[$row->state] }} text-[13px] font-semibold py-1 px-2 flex items-center justify-between">
                                    {{ ucfirst($row->state) }}
                                    <svg class="w-4 h-4 ml-2 {{ $statesText[$row->state] }}" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                        <td class="py-3 px-6">
                            @if ($row->mechanics->isNotEmpty())
                                {{ $row->mechanics->first()->name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="py-3 px-6">
                            @if ($row->customerInfo)
                                {{ $row->customerInfo->name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="py-3 px-6">
                            @if ($row->customerInfo)
                                {{ $row->customerInfo->admin_name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="py-3 px-6">{{ $row->plate }}</td>
                        <td class="py-3 px-6">{{ $row->price }}€</td>
                        <td class="py-3 px-6 flex space-x-2">
                            @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\Order::class], key(str()->random(10)))
                            @livewire('delete-button', ['modelId' => $row->id, 'modelName' => 'orders', 'modelClass' => \App\Models\Order::class], key(str()->random(10)))
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-3 px-6 text-center">Nessun risultato</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($loadMore)
            <div class="py-4 text-center">
                <button wire:click="loadMoreOrders"
                    class="ml-4 py-1 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-full">
                    + Carica più risultati
                </button>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let isLoading = false;
        window.onscroll = function(ev) {

            if ((window.innerHeight + window.scrollY) >= document.body.scrollHeight) {
                if (!isLoading) {
                    isLoading = true;
                    Livewire.dispatch('loadMoreOrders');
                    setTimeout(() => isLoading = false, 800);
                }
            }
        };
    });
</script>
