<div class="p-4">
    <div class="mb-4">
        <input type="text" class="p-2 border border-gray-300 rounded" placeholder="Search..."
            wire:model.debounce.300ms.live="searchTerm" />
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="w-full bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                    @foreach($headers as $header => $field)
                    <th class="py-3 px-6">{{ $header }}</th>
                    @endforeach
                    <th class="py-3 px-6">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse($rows as $row)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    @foreach($headers as $field)
                    <td class="py-3 px-6">{{ $row->$field }}</td>
                    @endforeach
                    <td class="py-3 px-6 flex space-x-2">
                        @foreach($actions as $action)
                        <button wire:click="{{ $action }}({{ $row->id }})"
                            class="text-blue-500 hover:text-blue-700 focus:outline-none">
                            {{ ucfirst($action) }}
                        </button>
                        @endforeach
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($headers) + 1 }}" class="py-3 px-6 text-center">No records found</td>
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