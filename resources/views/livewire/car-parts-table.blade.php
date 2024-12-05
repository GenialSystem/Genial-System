<table class="w-full mt-6 bg-white border border-gray-200">
    <thead class="bg-[#F5F5F5]">
        <tr class="w-full text-left text-gray-600 text-sm leading-normal">
            <th class="h-6 text-[15px] text-[#808080] font-light">Elementi</th>
            <th class="h-6 text-[15px] text-[#808080] font-light">N. Bolli</th>
            <th class="h-6 text-[15px] text-[#808080] font-light text-center">Preparazione verniciatura</th>
            <th class="h-6 text-[15px] text-[#808080] font-light text-center">Sostituzione</th>
        </tr>
    </thead>
    <tbody class="text-sm text-[#222222]">
        @foreach ($carParts as $part)
            <tr class="text-sm text-[#222222] border-b">
                <td class="py-2 px-2">{{ $part->name }}</td>
                <td class="py-2 px-2">{{ $part->pivot->damage_count ?? 0 }}</td>
                <td class="py-2 px-2 text-center">
                    <input type="checkbox" @disabled(Auth::user()->hasRole('customer'))
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        wire:change="updatePivotField({{ $part->id }}, 'paint_prep', $event.target.checked)"
                        {{ $part->pivot->paint_prep ? 'checked' : '' }}>
                </td>
                <td class="py-2 px-2 text-center">
                    <input type="checkbox" @disabled(Auth::user()->hasRole('customer'))
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        wire:change="updatePivotField({{ $part->id }}, 'replacement', $event.target.checked)"
                        {{ $part->pivot->replacement ? 'checked' : '' }}>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
