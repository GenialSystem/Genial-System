<div class="bg-white">
    <div class="mb-4 h-8">
        <input type="text" class="p-2 border border-gray-300 rounded h-8 w-[600px]" placeholder="Cerca elemento"
            wire:model.debounce.300ms.live="searchTerm" />
    </div>
    <div class="overflow-x-auto rounded-md">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-[#F5F5F5]">
                <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N° riparazioni</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Data inizio</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Data fine</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Cliente</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Targa/Telaio</th>
                    <th class="py-3 px-6"></th>
                </tr>
            </thead>
            <tbody class=" text-sm text-[#222222]">

                @forelse($orders as $row)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">#{{ $row->id }}</td>
                        <td class="py-3 px-6">{{ $row->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-6">{{ $row->created_at->format('d M Y') }}</td>
                        <td class="py-3 px-6">{{ $row->customer->name }}</td>
                        <td class="py-3 px-6">{{ $row->plate }}</td>
                        <td class="py-3 px-6">
                            @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\Order::class], key(str()->random(10)))
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-3 px-6 text-center">Nessun risultato</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
