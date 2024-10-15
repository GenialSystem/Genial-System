<div class="bg-white 2xl:w-3/5 p-6 rounded-lg mt-4 w-full relative h-full">
    <!-- Loading Overlay -->
    <div wire:loading class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-10">
        <div class="flex place-content-center place-items-center h-full">
            <!-- Spinner Icon -->
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
            <span class="text-[#222222] font-semibold">Caricamento...</span>
        </div>
    </div>

    <!-- Form -->
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-[#222222] mb-0">Richiesta nuova riparazione</h3>
    </div>

    <form wire:submit.prevent="submit" class="relative">
        <!-- Description Field -->
        <label class="block text-sm text-[13px] text-[#9F9F9F]" for="description">Descrizione riparazione</label>
        <textarea required wire:model="description" id="description" cols="20" rows="5"
            class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none border-[#F0F0F0] mb-3"
            wire:loading.attr="disabled"></textarea>
        @error('description')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror

        <!-- Date Field -->
        <div class="grid grid-cols-3 gap-4">
            <div>
                <label for="date" class="block text-[13px] text-[#9F9F9F] mb-1">Data</label>
                <div class="relative flex items-center">
                    <input required wire:model="date" type="date" id="date"
                        class="block w-full px-3 py-2 border rounded-md focus:outline-none border-[#F0F0F0] focus:ring-0"
                        wire:loading.attr="disabled">
                    <div class="absolute top-0 bottom-0 right-0 flex justify-center items-center bg-[#F2F1FB] w-10 rounded-r cursor-pointer"
                        onclick="document.getElementById('date').showPicker()">
                        <img src="{{ asset('images/calendar icon.svg') }}" class="w-4 h-4" alt="calendar icon">
                    </div>
                </div>
                @error('date')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Mechanic Field -->
            <div class="mb-4 relative">
                <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Tecnico</label>
                <select required wire:model="selectedMechanic"
                    class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none border-[#F0F0F0] appearance-none"
                    wire:loading.attr="disabled">
                    <option value="">Seleziona un tecnico</option>
                    @foreach ($mechanics as $mechanic)
                        <option value="{{ $mechanic->id }}">{{ $mechanic->user->name }} {{ $mechanic->user->surname }}
                        </option>
                    @endforeach
                </select>
                @error('selectedMechanic')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Brand Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Marca</label>
                <input wire:model="brand" required type="text"
                    class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none border-[#F0F0F0]"
                    wire:loading.attr="disabled">
                @error('brand')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <!-- Plate Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Targa/Telaio</label>
                <input wire:model="plate" required type="text"
                    class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none border-[#F0F0F0]"
                    wire:loading.attr="disabled">
                @error('plate')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Immatricolazione Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Immatricolazione</label>
                <input wire:model="immatricolazione" required type="text"
                    class="mt-1 block w-full px-3 py-2 borde rounded-md focus:outline-none border-[#F0F0F0]"
                    wire:loading.attr="disabled">
                @error('immatricolazione')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- Km Field -->
            <!-- Km Field -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">N° km</label>
                <input wire:model="km" name="km" required type="number" id="km" min="1"
                    step="1"
                    class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none border-[#F0F0F0]"
                    wire:loading.attr="disabled">
                @error('km')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

        </div>

        <!-- Submit Button -->
        <div class="text-end h-8 mt-10">
            <button type="submit" class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm"
                wire:loading.attr="disabled">
                Conferma
            </button>
        </div>
    </form>
</div>
