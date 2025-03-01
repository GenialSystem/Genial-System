    <div>
        <div class="p-6 rounded-lg shadow-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-[#222222] mb-0">
                    {{ count($selectedRows) }} Elementi selezionati
                </h3>
                <button wire:click="$dispatch('closeModal')" class="text-gray-500 hover:text-[#9F9F9F] text-3xl">
                    &times;
                </button>
            </div>


            <span class="text-[#222222] text-[15px]">Modifica lo stato degli elementi selezionati</span>
            <form wire:submit.prevent="applyStateToSelectedRows" class="mt-5">
                <div class="mb-4">
                    <label for="state" class="block text-[#9F9F9F] text-[13px]">Stato riparazione</label>
                    <select id="state" wire:model="newState" class="mt-2 p-2 border border-gray-300 rounded w-full">
                        <option value="">- Seleziona -</option>
                        @foreach ($states as $state => $color)
                            <option value="{{ $state }}">{{ ucfirst($state) }}</option>
                        @endforeach
                    </select>
                    @error('newState')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-end">

                    <button type="submit" class="mt-3 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-8"
                        wire:loading.remove>
                        Conferma
                    </button>
                    <button disabled class="mt-3 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-8" wire:loading>
                        <div class="flex items-center">

                            <div
                                class="animate-spin rounded-full h-5 w-5 border-t-2 border-b-2 border-white] border-t-transparent mr-2">
                            </div>
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
