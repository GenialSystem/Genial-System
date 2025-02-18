<div>
    <div class="bg-white">

        <div class="mb-4 flex justify-between h-8">
            <input type="text" class="p-2 border border-gray-300 rounded w-[600px]" placeholder="Cerca elemento..."
                wire:model.debounce.300ms.live="searchTerm" />
            <div class="mb-4">

                <select class="border border-gray-300 rounded pl-2 pr-20 h-8 leading-none"
                    wire:model.live="selectedState">
                    <option value="">Tutti</option>
                    @foreach ($states as $state => $color)
                        <option value="{{ $state }}">{{ ucfirst($state) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="overflow-x-auto rounded-md">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-[#F5F5F5]">
                    <tr class="w-full text-left text-gray-600 text-sm leading-normal">

                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N. Riparazione</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Stato Riparazione</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Targa/Telaio</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Tecnico</th>

                        <th class="py-3 px-6"></th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#222222]">
                    @forelse($rows as $row)
                        <tr translate="no" class="border-b border-gray-200 hover:bg-gray-100">

                            <td class="py-3 px-6">{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3 px-6 relative" wire:key="order-{{ $row->id }}">
                                <div x-data="{ open: false }" class="relative">
                                    <!-- Dropdown Button -->
                                    <button @click="open = !open"
                                        class="block w-full text-left rounded {{ $states[$row->state] }} {{ $statesText[$row->state] }} text-[13px] font-semibold py-1 px-2 flex items-center justify-between">
                                        {{ ucfirst( __('orders.' . $row->state) ) }}
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
                                                    {{ ucfirst( __('orders.' . $state) ) }}
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
                                            <img class="inline-block w-8 h-8  rounded-full border border-black"
                                                src="{{ asset($mechanic->image_path ?? 'images/placeholder.png') }}"
                                                alt="profile image">
                                        @endforeach
                                    </div>
                                    <div
                                        class="absolute right-0 top-4 mt-1 hidden group-hover:block bg-gray-700 text-white text-xs rounded-lg px-2 py-1 whitespace-nowrap z-10">
                                        @foreach ($row->mechanics as $mechanic)
                                            {{ $mechanic->name }} {{ $mechanic->surname }}<br>
                                        @endforeach
                                    </div>
                                @else
                                    N/A
                                @endif
                            </td>


                            <td class="py-3 px-6 flex space-x-2">
                                @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\Order::class], key(str()->random(10)))
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
