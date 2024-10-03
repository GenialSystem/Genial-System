<div class="relative">
    @if (count($selectedRows) > 0)
        <div class="fixed bottom-14 left-1/2 transform -translate-x-1/2 flex rounded-sm bg-white shadow-md">
            <div class="bg-[#7FBC4B] p-5 flex items-center space-x-2">
                <span class="text-white text-2xl font-bold">{{ count($selectedRows) }}</span>
                <span class="text-white text-lg">Elementi selezionati</span>
            </div>

            <div class="flex space-x-2">
                @foreach ($actionButtons as $button)
                    @if ($button === 'delete')
                        <button wire:key="{{ str()->random(10) }}"
                            wire:click="$dispatch('openModal', { 
                            component: 'delete-button-modal', 
                            arguments: { 
                                modelIds: @js($selectedRows), 
                                modelClass: '{{ addslashes($modelClass) }}',  
                            } 
                        })"
                            class="text-[#222222] px-4 py-2 rounded">
                            Elimina
                        </button>
                    @elseif ($button === 'edit')
                        <button wire:key="{{ str()->random(10) }}"
                            wire:click="$dispatch('openModal', {
                        component: 'update-order-state-modal',
                        arguments: {
                            selectedRows: @js($selectedRows)
                        }
                    })"
                            class="text-[#222222] px-4 py-2 rounded">
                            Modifica
                        </button>
                    @elseif ($button === 'download')
                        <button wire:key="{{ str()->random(10) }}" class="text-[#222222] px-4 py-2 rounded">
                            Scarica
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
    @endif


</div>
