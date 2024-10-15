<div class="p-6">
    <form wire:submit.prevent="submit" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-4">Carica documenti</label>

            <!-- Custom File Upload Button -->
            <div class="flex items-center justify-center w-full">
                <label for="files"
                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-[#F2F1FB] hover:bg-[#E6E6F2]">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <p class="mb-2 text-sm text-gray-500">
                            <span class="font-semibold">Clicca per caricare documenti</span>
                        </p>
                        <p class="text-xs text-gray-500">PNG, JPG, PDF fino a 10MB</p>
                    </div>
                    <input id="files" accept=".jpg,.jpeg,.png,.pdf,.svg" type="file" wire:model="files" multiple
                        class="hidden">
                </label>
            </div>

            <!-- Error Message -->
            @error('files.*')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror

            <!-- Loading Indicator -->
            <div wire:loading wire:target="files" class="text-sm text-gray-500 mt-2">Attendi...</div>
        </div>

        <!-- Display Selected Files -->
        @if ($files)
            <div class="mt-4">
                <h4 class="text-sm font-semibold">File selezionati:</h4>
                <ul class="list-disc pl-5 text-gray-600">
                    @foreach ($files as $file)
                        <li>{{ $file->getClientOriginalName() }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Buttons -->
        <div class="text-end h-8 mt-10">
            <button type="button" wire:click="$dispatch('closeModal')"
                class="mr-4 py-2 px-4 bg-[#E8E8E8] text-[#222222] rounded-md text-sm">Annulla</button>
            <button type="submit" class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm">Conferma</button>
        </div>
    </form>
</div>
