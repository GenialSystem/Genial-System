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
                        <button wire:key="{{ str()->random(10) }}" wire:click="deleteSelectedRows"
                            class="text-[#222222] px-4 py-2 rounded">
                            Elimina
                        </button>
                    @elseif ($button === 'edit')
                        <button wire:click="openModal" class="text-[#222222] px-4 py-2 rounded">
                            Modifica
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    {{-- Modal for Delete Confirmation --}}
    @if ($showDeleteModal)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 z-40 bg-black bg-opacity-50"></div> <!-- Background with opacity -->

        <!-- Modal Content -->
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-lg w-full">
                <div class="px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                    <h5 class="text-lg font-bold">Conferma eliminazione</h5>
                    <button type="button" wire:click="closeDeleteModal" class="text-gray-400 hover:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex justify-center my-4">
                    <img src="{{ asset('images/allert.svg') }}" alt="delete-alert">
                </div>

                <div class="px-4 py-5 border-t border-gray-200 flex justify-between">
                    <button type="button" wire:click="closeDeleteModal"
                        class="bg-[#E8E8E8] rounded-md p-1.5 text-[#222222]">
                        Annulla
                    </button>
                    <button type="button" wire:click="delete" class="bg-[#DC0851] rounded-md p-1.5 text-white">
                        Elimina
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
