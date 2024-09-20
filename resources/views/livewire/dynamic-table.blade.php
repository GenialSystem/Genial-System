{{-- @php
function getNestedAttribute($row, $attribute)
{
// Split by '->' to handle nested attributes
$attributes = explode('->', $attribute);
$value = $row;

// Check if the attribute contains collection indexing
if (strpos($attributes[0], '[') !== false) {
// Extract the base attribute and index
preg_match('/(.*)\[(\d+)\]/', $attributes[0], $matches);
$collectionAttribute = $matches[1];
$index = $matches[2];

// Access the collection attribute
$value = $value->$collectionAttribute ?? null;

if (is_array($value) || $value instanceof \Illuminate\Support\Collection) {
// Get the item at the specified index
$value = $value->get($index) ?? null;
} else {
return null; // Invalid collection or index
}

// Remove the base attribute part from the attributes
array_shift($attributes);
}

// Handle nested attributes
foreach ($attributes as $attr) {
// Check if the current attribute is a relation that returns a collection
if (method_exists($value, 'pluck')) {
// Join collection values into a comma-separated string
$value = $value->pluck($attr)->join(', ');
} else {
$value = $value->$attr ?? null;
}

if (is_null($value)) {
break;
}
}

return $value;
}
@endphp


<div class="mt-4 bg-white p-4">
    <h3 class="text-[#222222] text-lg font-semibold mb-4">{{ $title }}</h3>
    @if ($hasInput)
    <div class="mb-4">
        <input type="text" class="p-2 border border-gray-300 rounded" placeholder="Search..."
            wire:model.debounce.300ms.live="searchTerm" />
    </div>
    @endif
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-[#F5F5F5]">
                <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                    @foreach ($headers as $header => $field)
                    <th class="py-3 px-6 text-[15px] text-[#808080] font-light">{{ $header }}</th>
                    @endforeach
                    <th class="py-3 px-6"></th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse($rows as $row)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    @foreach ($headers as $field)
                    <td class="py-3 px-6">{{ getNestedAttribute($row, $field) }}</td>
                    @endforeach
                    <td class="py-3 px-6 flex space-x-2">
                        @foreach ($actions as $action)
                        <button wire:click="{{ $action }}({{ $row->id }})"
                            class="text-blue-500 hover:text-blue-700 focus:outline-none">
                            {{ ucfirst($action) }}
                        </button>
                        @endforeach
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ count($headers) + 1 }}" class="py-3 px-6 text-center">Nessun risultato</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $rows->links() }}
        </div>
    </div>
</div> --}}
