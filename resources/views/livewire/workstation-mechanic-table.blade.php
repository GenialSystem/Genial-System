<div class="bg-white">
    <div class="mb-4 h-8">
        <input type="text" class="p-2 border border-gray-300 rounded h-8 w-[600px]" placeholder="Cerca elemento"
            wire:model.debounce.300ms.live="searchTerm" />
    </div>
    <div class="overflow-x-auto rounded-md">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-[#F5F5F5]">
                <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Tecnico</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Postazioni di lavoro</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto in coda</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Presunta durata della postazione</th>

                    <th class="py-3 px-6"></th>
                </tr>
            </thead>
            <tbody class=" text-sm text-[#222222]">

                @forelse($mechanics as $row)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 flex place-items-center"> <span
                                class="inline-block w-8 h-8 bg-red-300 rounded-full border border-black mr-3">
                            </span>{{ $row->user->name }} {{ $row->user->surname }}</td>
                        <td class="py-3 px-6">Postazione lavoro</td>
                        <td class="py-3 px-6 text-center">{{ $row->working_count }}</td>
                        <td class="py-3 px-6 text-center">10h</td>
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
