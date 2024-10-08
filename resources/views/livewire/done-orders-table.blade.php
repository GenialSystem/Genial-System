<div class="mt-4">
    <h3 class="text-[#222222] text-2xl font-semibold mb-4">Riepilogo auto riparate</h3>
    <div class="bg-white p-4">

        <div class="mb-4">
            <input type="text" class="p-2 border border-gray-300 rounded h-8 w-[600px]" placeholder="Cerca elemento..."
                wire:model.debounce.300ms.live="searchTerm" />
        </div>
        <!-- text-[#DC76E0] bg-[#FFF2FF] bg-[#FCE5E8] bg-[#E7FAF4] bg-[#D6D6D6] bg-[#F5F5F5] bg-[#FAF2DD] text-[#DC0814] text-[#92D1BB] text-[#464646] text-[#9F9F9F] text-[#E8C053] -->


        <div class="overflow-x-auto rounded-md">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-[#F5F5F5]">
                    <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Cliente</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Responsabile</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Città</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Marca/Modello</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Targa/Telaio</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N. Bolli</th>

                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Tecnico</th>
                        <th class="py-3 px-6"></th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#222222]">
                    @forelse($rows as $row)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $row->customer->user->name }} {{ $row->customer->user->surname }}
                            </td>
                            <td class="py-3 px-6">
                                {{ $row->customer ? $row->customer->admin_name : 'N/A' }}
                            </td>
                            <td class="py-3 px-6 relative">
                                {{ $row->customer->user->city }}
                            </td>

                            <td class="py-3 px-6">{{ $row->brand }}</td>
                            <td class="py-3 px-6">{{ $row->plate }}</td>
                            <td class="py-3 px-6">
                                {{ $row->carParts->sum('pivot.damage_count') }}
                            </td>
                            <td class="py-3 px-6">
                                <div class="flex place-items-center">
                                    @if ($row->mechanics->isNotEmpty())
                                        <img class="inline-block w-8 h-8  rounded-full border mr-2"
                                            src="{{ asset($row->mechanics->first()->image_path ?? 'images/placeholder.png') }}"
                                            alt="profile image">
                                        {{ $row->mechanics->first()->name }}
                                        {{ $row->mechanics->first()->surname }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-6 flex space-x-2">
                                @livewire('show-button', ['customRoute' => 'showDoneOrder', 'modelId' => $row->id, 'modelClass' => \App\Models\Order::class], key(str()->random(10)))
                                {{-- @livewire('edit-button', ['modelId' => $row->id, 'modelName' => 'order', 'modelClass' => \App\Models\Order::class], key(str()->random(10))) --}}
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
