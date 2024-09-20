<div class="bg-[#FEF0F5] w-6 p-1 flex items-center justify-center group hover:bg-[#DC0814] duration-200 rounded-sm">
    <button wire:click="confirmDelete" class="flex items-center justify-center">
        <svg id="Raggruppa_3305" data-name="Raggruppa 3305" xmlns="http://www.w3.org/2000/svg" width="13.996" height="15.733"
            viewBox="0 0 13.996 15.733">
            <path class="group-hover:fill-white" id="Tracciato_731" data-name="Tracciato 731"
                d="M37.38,21.567H33.988v-.99A1.389,1.389,0,0,0,32.6,19.19H29.236a1.389,1.389,0,0,0-1.387,1.387v.99H24.457a.537.537,0,1,0,0,1.073h1.012V31.88a3.047,3.047,0,0,0,3.043,3.043h4.814a3.047,3.047,0,0,0,3.043-3.043V22.641H37.38a.537.537,0,1,0,0-1.073ZM35.295,31.88a1.971,1.971,0,0,1-1.97,1.97H28.511a1.971,1.971,0,0,1-1.97-1.97v-9.2h8.753Zm-6.373-11.3a.314.314,0,0,1,.314-.314H32.6a.314.314,0,0,1,.314.314v.99H28.922Z"
                transform="translate(-23.92 -19.19)" fill="#dc0851" />
            <path class="group-hover:fill-white" id="Tracciato_732" data-name="Tracciato 732"
                d="M42.589,48.265a.591.591,0,0,0,.589-.589v-5.9a.589.589,0,0,0-1.179,0v5.9A.591.591,0,0,0,42.589,48.265Z"
                transform="translate(-37.188 -35.688)" fill="#dc0851" />
            <path class="group-hover:fill-white" id="Tracciato_733" data-name="Tracciato 733"
                d="M54.589,48.265a.591.591,0,0,0,.589-.589v-5.9a.589.589,0,0,0-1.179,0v5.9A.591.591,0,0,0,54.589,48.265Z"
                transform="translate(-45.994 -35.688)" fill="#dc0851" />
        </svg>

    </button>
    <!-- Delete Confirmation Modal -->
    @if ($showModal)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <!-- Modal Content -->
            <div class="bg-white rounded-lg shadow-lg max-w-lg w-full">
                <div class="px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                    <h5 class="text-lg font-bold">Conferma eliminazione</h5>
                    <button type="button" wire:click="$set('showModal', false)"
                        class="text-gray-400 hover:text-gray-500">
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

                <div class="px-4 py-2 border-t border-gray-200 flex justify-between">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="bg-[#E8E8E8] rounded-md p-1.5 text-[#222222]">
                        Annulla
                    </button>
                    <button type="button" wire:click="delete" class="bg-[#DC0851] rounded-md p-1.5 text-white">
                        Elimina
                    </button>
                </div>
            </div>
        </div>

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900 opacity-50 z-40"></div>
    @endif
</div>
