<div class="mt-4">
    <h3 class="text-[#222222] text-2xl font-semibold mb-4">Riepilogo auto riparate</h3>
    <div class="bg-white p-4">

        <div class="mb-4">
            <input type="text" class="p-2 border border-gray-300 rounded h-8 w-full xl:w-[600px]"
                placeholder="Cerca elemento..." wire:model.debounce.300ms.live="searchTerm" />
        </div>
        <!-- text-[#DC76E0] bg-[#FFF2FF] bg-[#FCE5E8] bg-[#E7FAF4] bg-[#D6D6D6] bg-[#F5F5F5] bg-[#FAF2DD] text-[#DC0814] text-[#92D1BB] text-[#464646] text-[#9F9F9F] text-[#E8C053] -->

        <x-top-scrollbar id="top-scrollbar" />

        <div id="table" class="overflow-x-auto rounded-md">
            <table id="get-width" class="min-w-full bg-white border border-gray-200 whitespace-nowrap">
                <thead class="bg-[#F5F5F5]">
                    <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Cliente</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Marca/Modello</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Targa/Telaio</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Colore</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Prezzo</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Stato</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Tecnico</th>

                        <th class="py-3 px-6"></th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#222222]">
                    @forelse($rows as $row)
                        <tr translate="no" class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $row->customer->user->name }} {{ $row->customer->user->surname }}
                            </td>
                            <td class="py-3 px-6">{{ $row->brand }}</td>
                            <td class="py-3 px-6">{{ $row->plate }}</td>
                            <td class="py-3 px-6">{{ $row->color }}</td>
                            <td class="py-3 px-6">{{ $row->price }} €</td>
                            <td class="py-3 px-6">{{ $row->payment }}</td>

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
{{-- script per sincronia tra scrollbar superiore e inferiore (la scrollbar inferiore è quella legit del browser) --}}
<script src="{{ asset('js/top-scrollbar.js') }}"></script>
