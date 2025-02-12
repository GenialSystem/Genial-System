<div class="mt-4 bg-white p-4">
    <div class="mb-4 space-y-3 2xl:space-y-0 2xl:flex justify-between">
        <input type="text" class="p-2 border border-gray-300 rounded h-8 w-full xl:w-[600px]"
            placeholder="Cerca elemento..." wire:model.debounce.300ms.live="searchTerm" />
        <div class="flex justify-between 2xl:space-x-4">
            @livewire('date-filter')

            <a href="{{ route('customers.create') }}"><button
                    class="h-8 px-2 bg-[#1E1B58] text-white rounded-md text-sm">+
                    Crea nuovo cliente</button></a>
        </div>
    </div>

{{-- componente blade con il div lungo quanto la tabella, questo div servirà per vedere la scrollbar superiore --}}
<x-top-scrollbar id="top-scrollbar" />

    <!-- bg-[#FFF9EC] bg-[#E9EFF5] bg-[#EFF7E9] text-[#FCC752] text-[#7FBC4B] text-[#5E66CC] text-[#DC0851] bg-[#FEF0F5] -->
    <div id="table" class="overflow-x-auto rounded-md">
        <table id="get-width" wire:key="{{ str()->random(10) }}" class="get-width min-w-full bg-white border border-gray-200 whitespace-nowrap">
            <thead class="bg-[#F5F5F5]">
                <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Cliente</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Responsabile</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Città</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto assegnate</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto in coda</th>
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto riparate</th>

                    <th class="py-3 px-6"></th>
                </tr>
            </thead>
            <tbody class=" text-sm text-[#222222]">
                @forelse($rows as $row)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ $row->user->name }} {{ $row->user->surname }}</td>
                        <td class="py-3 px-6">{{ $row->admin_name }}</td>
                        <td class="py-3 px-6">{{ $row->user->city }}</td>
                        <td class="py-3 px-6">{{ $row->assigned_cars_count }}</td>
                        <td class="py-3 px-6">{{ $row->queued_cars_count }}</td>
                        <td class="py-3 px-6">{{ $row->finished_cars_count }}</td>

                        <td class="py-3 px-6 flex space-x-2">
                            @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\CustomerInfo::class], key(str()->random(10)))
                            @livewire('edit-button', ['modelId' => $row->id, 'modelName' => 'customer', 'modelClass' => \App\Models\CustomerInfo::class], key(str()->random(10)))
                            @livewire('delete-button', ['modelId' => $row->id, 'modelName' => 'customers', 'modelClass' => \App\Models\CustomerInfo::class], key(str()->random(10)))
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-3 px-6 text-center">Nessun risultato</td>
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
