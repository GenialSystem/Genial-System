<div class="mt-4 bg-white p-4">
    <div class="mb-4 flex justify-between h-8">
        <input type="text" class="p-2 border border-gray-300 rounded w-[600px]" placeholder="Cerca elemento..."
            wire:model.debounce.300ms.live="searchTerm" />
        <div class="flex space-x-3">
            @livewire('date-filter')
            @livewire('mechanic-form')
        </div>
    </div>
    <!-- bg-[#FFF9EC] bg-[#E9EFF5] bg-[#EFF7E9] text-[#FCC752] text-[#7FBC4B] text-[#5E66CC] text-[#DC0851] bg-[#FEF0F5] -->
    <div class="overflow-x-auto rounded-md">
        <table wire:key="1" class="min-w-full bg-white border border-gray-200">
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
            <tbody class=" text-sm text-[#222222]">

                @forelse($rows as $row)
                    <tr wire:key="{{ str()->random(10) }}" class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ $row->user->name }}</td>
                        <td class="py-3 px-6">{{ $row->user->email }}</td>
                        <td class="py-3 px-6">{{ $row->user->cellphone }}</td>
                        <td class="py-3 px-6">{{ $row->user->city }}</td>
                        <td class="py-3 px-6">

                            <div
                                class="w-8 h-8 rounded-full bg-[#68C9BB] text-lg text-white text-center place-content-center">
                                {{ $row->repaired_count }}
                            </div>

                        </td>
                        <td class="py-3 px-6">

                            <div
                                class="w-8 h-8 rounded-full bg-[#CECEF7] text-lg text-center text-[#805ECC] place-content-center">
                                {{ $row->working_count }}
                            </div>
                        </td>

                        <td class="py-3 px-6 flex space-x-2">
                            @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\MechanicInfo::class], key(str()->random(10)))

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
