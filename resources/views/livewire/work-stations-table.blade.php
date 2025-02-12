<div class="mt-4">
    <h3 class="text-[#222222] text-2xl font-semibold mb-4">Postazioni</h3>
    <div class="bg-white p-4">

        <div class="mb-4 flex justify-between h-8">
            <input type="text" class="p-2 border border-gray-300 rounded" placeholder="Cerca elemento..."
                wire:model.debounce.300ms.live="searchTerm" />
            <div class="flex">
                <button wire:click="$dispatch('openModal', { component: 'workstation-modal' })"
                    class="ml-4 py-1 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-full">+
                    Crea
                    nuova
                    postazione lavoro</button>
            </div>
        </div>
            <x-top-scrollbar id="top-scrollbar" />

        <div id="table" class="overflow-x-auto rounded-md">
            <table id="get-width" class="min-w-full bg-white border border-gray-200 whitespace-nowrap">
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
                            <td class="py-3 px-6">{{ $row->city }}</td>
                            <td class="py-3 px-6">{{ $row->address }}</td>
                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEEDFA] text-lg text-center text-[#1E1B58] place-content-center">
                                    {{ count($row->mechanics) }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEEDFA] text-lg text-center text-[#1E1B58] place-content-center">
                                    {{ $row->totalAssignedCars() }}
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
                                    {{ $row->totalInProgressCars() }}
                                </div>
                            </td>

                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEEDFA] text-lg text-center text-[#1E1B58] place-content-center">
                                    {{ $row->totalInQueueCars() }}

                                </div>
                            </td>
                            <td class="py-4 px-6 flex space-x-2">
                                @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\Workstation::class], key(str()->random(10)))
                                <div
                                    class="bg-[#EDF8FB] w-6 p-1 flex items-center justify-center group hover:bg-[#66C0DB] duration-200 rounded-sm">
                                    <button
                                        wire:click="$dispatch('openModal', { component: 'workstation-modal', arguments:{'workstation': {{ $row }}} })"
                                        class="flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15.559" height="20.152"
                                            viewBox="0 0 15.559 20.152" class="group-hover:fill-white">
                                            <g id="noun-pencil-3690771" transform="translate(-7.171 -0.615)">
                                                <g id="Layer_19" data-name="Layer 19"
                                                    transform="translate(7.48 0.946)">
                                                    <!-- Apply fill and hover effects directly to the path -->
                                                    <path id="Tracciato_755" data-name="Tracciato 755"
                                                        d="M1.085,16.985a.43.43,0,0,0,.617.263l3.5-1.874a.43.43,0,0,0,.192-.21L9.939,4.493h0l.83-1.948a.429.429,0,0,0-.226-.563L5.977.035a.43.43,0,0,0-.564.227L.034,12.879a.431.431,0,0,0-.018.284ZM6.035.993,9.811,2.6,9.318,3.759,5.545,2.143Zm-.827,1.94L8.981,4.55,4.659,14.687l-2.892,1.55L.884,13.078Z"
                                                        transform="matrix(0.966, 0.259, -0.259, 0.966, 4.477, 0)"
                                                        fill="#66c0db"
                                                        class="group-hover:fill-white transition-colors duration-200" />
                                                </g>
                                            </g>
                                        </svg>
                                    </button>
                                </div>

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

</div>
{{-- script per sincronia tra scrollbar superiore e inferiore (la scrollbar inferiore è quella legit del browser) --}}
<script src="{{ asset('js/top-scrollbar.js') }}"></script>
