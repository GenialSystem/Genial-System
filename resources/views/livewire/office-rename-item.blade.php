<div class="p-6">
    <h2 class="text-xl font-bold mb-4">
        @if ($type === 'file')
            Rinomina File
        @else
            Rinomina Cartella
        @endif
    </h2>

    <form wire:submit.prevent="submit"> <!-- Ensure this is wire:submit.prevent -->
        <!-- Folder Name Input -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nuovo nome</label>
            <input required type="text" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md">
            @error('name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="text-end mt-4">
            <button type="submit" class="py-2 px-4 bg-[#1E1B58] text-white rounded">Rinomina</button>
        </div>
    </form>
</div>
