<div class="mt-4 bg-white p-4">
    <div class="mb-4 space-y-3 2xl:space-y-0 2xl:flex justify-between">
        <input type="text" class="p-2 border border-gray-300 rounded w-full xl:w-[600px] h-8"
            placeholder="Cerca elemento..." wire:model.debounce.300ms.live="searchTerm" />
        <div class="flex justify-between space-x-3">
            @livewire('date-filter')
            <button wire:click="$dispatch('openModal', { component: 'mechanic-form'})"
                class="px-2 bg-[#1E1B58] text-white rounded-md text-sm h-8">
                + Crea nuovo tecnico
            </button>
        </div>
    </div>
    <x-top-scrollbar id="top-scrollbar" />

    <!-- bg-[#FFF9EC] bg-[#E9EFF5] bg-[#EFF7E9] text-[#FCC752] text-[#7FBC4B] text-[#5E66CC] text-[#DC0851] bg-[#FEF0F5] -->
    <div  id="table" class="overflow-x-auto rounded-md">
        <table id="get-width" class="min-w-full bg-white border border-gray-200 whitespace-nowrap">
            <thead class="bg-[#F5F5F5]">
                <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Tecnico</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Email</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Cellulare</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Città</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto Riparate</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto in lavorazione</th>

                    <th class="py-3 px-6"></th>
                </tr>
            </thead>
            <tbody class="text-sm text-[#222222]">
                @forelse($rows as $row)
                    <tr wire:key="{{ str()->random(10) }}" class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">
                            <div class="flex place-items-center">

                                <img class="inline-block w-8 h-8  rounded-full border mr-2"
                                    src="{{ asset($row->user->image_path ?? 'images/placeholder.png') }}"
                                    alt="profile image">
                                {{ $row->user->name }}
                                {{ $row->user->surname }}

                            </div>
                        </td>
                        <td class="py-3 px-6">{{ $row->user->email }}</td>
                        <td class="py-3 px-6">{{ $row->user->cellphone }}</td>
                        <td class="py-3 px-6">{{ $row->user->city }}</td>
                        <td class="py-3 px-6 text-center">
                            <div
                                class="w-8 h-8 rounded-full bg-[#68C9BB] text-lg text-white text-center place-content-center">
                                {{ $row->repaired_count }}
                            </div>
                        </td>
                        <td class="py-3 px-6">
                            <div
                                class="w-8 h-8 rounded-full bg-[#CECEF7] text-lg text-center text-[#805ECC] place-content-center">
                                {{ $row->workingCount() }}
                            </div>
                        </td>
                        <td class="py-3 px-6 flex space-x-2">
                            @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\MechanicInfo::class], key(str()->random(10)))
                            <div
                                class="bg-[#FFF9EC] w-6 p-1 flex items-center justify-center group hover:bg-[#FFCD5D] duration-200 rounded-sm">
                                <a href="{{ route('mechanic-calendar', $row) }}">

                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="11.598" height="12.755"
                                            viewBox="0 0 11.598 12.755">
                                            <g id="calendar_icon" data-name="calendar icon"
                                                transform="translate(0.6 0.6)">
                                                <path class="transition-colors duration-200 group-hover:stroke-white"
                                                    id="Tracciato_101" data-name="Tracciato 101"
                                                    d="M5.655,6h8.088A1.155,1.155,0,0,1,14.9,7.155v8.088A1.155,1.155,0,0,1,13.743,16.4H5.655A1.155,1.155,0,0,1,4.5,15.243V7.155A1.155,1.155,0,0,1,5.655,6Z"
                                                    transform="translate(-4.5 -4.844)" fill="none" stroke="#222"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" />
                                                <path class="transition-colors duration-200 group-hover:stroke-white"
                                                    id="Tracciato_102" data-name="Tracciato 102"
                                                    d="M12.01,3V5.311M7.388,3V5.311M4.5,7.622H14.9"
                                                    transform="translate(-4.5 -3)" fill="none" stroke="#222"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" />
                                            </g>
                                        </svg>

                                    </button>
                                </a>
                            </div>
                            @livewire('delete-button', ['modelId' => $row->id, 'modelName' => 'mechanics', 'modelClass' => \App\Models\MechanicInfo::class], key(str()->random(10)))
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-3 px-6 text-center">No records
                            found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $rows->links() }}
        </div>
    </div>
</div>



{{-- script per sincronia tra scrollbar superiore e inferiore (la scrollbar inferiore è quella legit del browser) --}}
<script src="{{ asset('js/top-scrollbar.js') }}"></script>
