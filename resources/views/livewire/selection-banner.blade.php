<div class="relative">
    @if (count($selectedRows) > 0)
        <div class="fixed bottom-14 left-1/2 transform -translate-x-1/2 flex rounded-sm bg-white shadow-md">
            <div class="bg-[#7FBC4B] p-5 flex items-center space-x-2">
                <span class="text-white text-2xl font-bold">{{ count($selectedRows) }}</span>
                <span class="text-white text-lg">Elementi selezionati</span>
            </div>
            <div>
                <div wire:loading wire:target="applyStateToSelectedRows"
                    class="flex place-items-center place-content-center justify-center h-full px-10">
                    <!-- Spinner Icon -->
                    <svg class="animate-spin h-6 w-6 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>

                </div>
            </div>
            <div wire:loading.remove wire:target="applyStateToSelectedRows" class="flex space-x-2">
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
                        <button wire:key="{{ str()->random(10) }}"
                            wire:click="downloadPdfs('{{ $modelName }}', @js($selectedRows))"
                            class="text-[#222222] px-4 py-2 rounded">
                            Scarica
                        </button>
                    @elseif ($button === 'archive')
                        <button wire:key="{{ str()->random(10) }}" wire:click="applyStateToSelectedRows"
                            class="text-[#222222] px-4 py-2 rounded">
                            Archivia
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
    @endif


</div>
