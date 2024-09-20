<div class="mt-4">
    <h3 class="text-[#222222] text-2xl font-semibold mb-4">Fatture
        {{ $role == 'customer' ? 'Clienti' : 'Tecnici' }}</h3>
    <div class="bg-white p-4">

        <div class="mb-4 flex justify-between items-center h-8">
            <!-- Search Input -->
            <div>

                <input type="text" class="border border-gray-300 rounded mr-6 h-8 w-[600px]" placeholder="Search..."
                    wire:model.debounce.300ms.live="searchTerm" />

                <!-- is_closed Filter Dropdown -->
                <select wire:model.live="selectedIsClosed"
                    class="pr-12 border border-gray-300 rounded text-gray-600 text-sm h-full leading-none w-[225px]">
                    <option value="">Tutti</option>
                    <option value="0">Aperto</option>
                    <option value="1">Chiuso</option>
                </select>

                <!-- Customer or Mechanic Filter Dropdown -->
                @if ($role == 'mechanic')
                    <select wire:model.live="selectedCustomerOrMechanic"
                        class="pr-12 border border-gray-300 rounded text-gray-600 text-sm h-full leading-none w-[225px] ml-6">
                        <option value="">Tecnici</option>
                        @foreach ($mechanics as $mechanic)
                            <option value="{{ $mechanic->id }}">{{ $mechanic->name ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                @else
                    <select wire:model.live="selectedCustomerOrMechanic"
                        class="pr-12 border border-gray-300 rounded text-gray-600 text-sm h-full leading-none w-[225px] ml-6">
                        <option value="">Clienti</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name ?? 'N/A' }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            @if ($role == 'customer')
                <button class="py-1 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-full">+ Crea nuova
                    fattura</button>
            @endif
        </div>

        <div class="overflow-x-auto rounded-md">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-[#F5F5F5]">
                    <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">
                            <input type="checkbox"
                                class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                                wire:key="{{ str()->random(10) }}" wire:model.live="selectAll">
                        </th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Data</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N. Fattura</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Stato</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">
                            {{ $role == 'customer' ? 'Cliente' : 'Tecnici' }}</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Imponibile</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">IVA</th>
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
                            <td class="py-3 px-6">{{ $row->created_at->format('d M Y') }}</td>
                            <td class="py-3 px-6">{{ $row->id }}</td>
                            <td class="py-3 px-6 relative">
                                <div x-data="{ open: false }" class="relative">
                                    <!-- Dropdown Button -->
                                    <button @click="open = !open"
                                        class="block w-full text-left rounded {{ $row->is_closed ? 'bg-[#F5F5F5] text-[#9F9F9F]' : 'bg-[#EFF7E9] text-[#7FBC4B]' }} text-[13px] font-semibold py-1 px-2 flex items-center justify-between">
                                        {{ $row->is_closed ? 'Chiuso' : 'Aperto' }}
                                        <!-- Chevron Icon -->
                                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                            <td class="py-3 px-6">{{ $row->user->name }}</td>
                            <td class="py-3 px-6">{{ $row->price }}€</td>
                            <td class="py-3 px-6"> {{ $row->iva }}€</td>
                            <td class="py-3 px-6 flex space-x-2">
                                @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\Invoice::class], key(str()->random(10)))
                                @livewire('edit-button', ['modelId' => $row->id, 'modelName' => 'invoice', 'modelClass' => \App\Models\Invoice::class], key(str()->random(10)))
                                @livewire('delete-button', ['modelId' => $row->id, 'modelName' => 'invoices', 'modelClass' => \App\Models\Invoice::class, 'customRedirect' => $role == 'customer' ? 'invoicesCustomer' : 'invoicesMechanic'], key(str()->random(10)))
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
        'modelClass' => App\Models\Invoice::class,
        'modelId' => $selectedRows,
        'buttons' => ['edit', 'delete', 'download'],
    ])
</div>