<div class="relative w-80">
    <input type="text" placeholder="Cerca..."
        class="w-full border-none text-gray-900 text-sm rounded-lg bg-[#F5F5F5] px-4 py-2 focus:ring-transparent"
        wire:model.debounce.300ms.live="query" />

    @if (strlen($query) > 1)
        <ul class="bg-white border border-gray-300 mt-2 rounded-lg shadow-lg absolute top-8 w-full">
            @forelse($results as $result)
                <li class="px-4 py-2 hover:bg-gray-100">
                    <a href="{{ $result['url'] }}">{{ $result['title'] }}</a>
                </li>
            @empty
                <li class="px-4 py-2">Nessun risultato trovato</li>
            @endforelse
        </ul>
    @endif
</div>
