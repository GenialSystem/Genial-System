<!-- Modal Overlay -->
<div class="flex items-center justify-center z-50 bg-transparent">
    <!-- Modal Content -->
    <div class="bg-white rounded-lg shadow-lg w-full">
        <div class="px-4 py-2 border-b border-gray-200 flex justify-between items-center">
            <h5 class="text-lg font-bold">Conferma eliminazione</h5>
            <button type="button" wire:click="$dispatch('closeModal')" class="text-gray-400 hover:text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex justify-center my-4">
            <img src="{{ asset('images/allert.svg') }}" alt="delete-alert">
        </div>

        <div class="px-4 py-2 border-t border-gray-200 flex justify-between">
            <button type="button" wire:click="$dispatch('closeModal')"
                class="bg-[#E8E8E8] rounded-md p-1.5 text-[#222222]">
                Annulla
            </button>
            <button type="button" wire:click="delete" class="bg-[#DC0851] rounded-md p-1.5 text-white">
                Elimina
            </button>
        </div>
    </div>

</div>
