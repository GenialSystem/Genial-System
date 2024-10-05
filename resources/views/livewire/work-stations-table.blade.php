<div class="mt-4">
    <h3 class="text-[#222222] text-2xl font-semibold mb-4">Postazioni</h3>
    <div class="bg-white p-4">

        <div class="mb-4 flex justify-between h-8">
            <input type="text" class="p-2 border border-gray-300 rounded" placeholder="Cerca elemento..."
                wire:model.debounce.300ms="searchTerm" />
            <div class="flex">
                <button wire:click="$dispatch('openModal', { component: 'workstation-modal' })"
                    class="ml-4 py-1 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-full">+
                    Crea
                    nuova
                    postazione lavoro</button>
            </div>
        </div>

        <div class="overflow-x-auto rounded-md">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-[#F5F5F5]">
                    <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Cliente</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Responsabile</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Città</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Indirizzo</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N. Tecnici</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N. Auto Assegnate</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto Riparate</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto in Lavorazione</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto in coda</th>
                        <th class="py-3 px-6"></th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#222222]">
                    @forelse($rows as $row)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">

                            <td class="py-3 px-6">{{ $row->customer->user->name }} {{ $row->customer->user->surname }}
                            </td>
                            <td class="py-3 px-6">{{ $row->customer->admin_name }}</td>
                            <td class="py-3 px-6">{{ $row->customer->user->city }}</td>
                            <td class="py-3 px-6">{{ $row->customer->user->address }}</td>
                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEEDFA] text-lg text-center text-[#1E1B58] place-content-center">
                                    {{ count($row->mechanics) }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEEDFA] text-lg text-center text-[#1E1B58] place-content-center">
                                    {{ $row->customer->assigned_cars_count }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#68C9BB] text-lg text-center text-white place-content-center">
                                    {{ $row->mechanics->sum('repaired_count') }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEEDFA] text-lg text-center text-[#805ECC] place-content-center">
                                    {{ $row->mechanics->sum('working_count') }}
                                </div>
                            </td>

                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEEDFA] text-lg text-center text-[#1E1B58] place-content-center">
                                    {{ $row->customer->queued_cars_count }}
                                </div>
                            </td>
                            <td class="py-4 px-6 flex space-x-2">
                                @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\Workstation::class], key(str()->random(10)))
                                @livewire('edit-button', ['modelId' => $row->id, 'modelName' => 'workstation', 'modelClass' => \App\Models\Workstation::class], key(str()->random(10)))
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
