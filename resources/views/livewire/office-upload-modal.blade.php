<div class="p-6">
    <h2 class="text-xl font-bold mb-4">
        @if ($type === 'file')
            Carica File
        @else
            Crea Cartella
        @endif
    </h2>

    <!-- File Upload Form -->
    @if ($type === 'file')
        <form wire:submit.prevent="submit">
            <!-- Select Folder (Optional) -->
            <div class="mb-4">
                <label for="parentFolder" class="block text-sm font-medium text-gray-700">Carica in cartella</label>
                <select wire:model="parentFolderId" class="mt-1 block w-full border-gray-300 rounded-md">
                    <option value="">Nessuna cartella (Office)</option>
                    @foreach ($folders as $folder)
                        <option value="{{ $folder->id }}">{{ $folder->name }}</option>
                    @endforeach
                </select>

            </div>

            <!-- File Input -->
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-700">Seleziona file</label>
                <input required type="file" wire:model="file" class="mt-1 block w-full border-gray-300 rounded-md">
                @error('file')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="text-end mt-4">
                <button type="submit" class="py-2 px-4 bg-[#1E1B58] text-white rounded disabled:opacity-50"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        Carica
                    </span>
                    <span wire:loading>
                        <!-- Add a spinner icon here. You can use an SVG or FontAwesome icon -->
                        <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0114.685-4.685A8 8 0 1012 4v8z">
                            </path>
                        </svg>
                    </span>
                </button>
            </div>

        </form>
    @endif

    <!-- Folder Creation Form -->
    @if ($type === 'folder')
        <form wire:submit.prevent="submit">
            <!-- Folder Name Input -->
            <div class="mb-4">
                <label for="folderName" class="block text-sm font-medium text-gray-700">Nome Cartella</label>
                <input required type="text" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md">
                @error('name')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Optional File Upload in New Folder -->
            <div class="mb-4">
                <label for="newFolderFile" class="block text-sm font-medium text-gray-700">Carica file
                    (opzionale)</label>
                <input type="file" wire:model="newFolderFile" class="mt-1 block w-full border-gray-300 rounded-md">
                @error('newFolderFile')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="text-end mt-4">
                <button type="submit" class="py-2 px-4 bg-[#1E1B58] text-white rounded">Crea Cartella</button>
            </div>
        </form>
    @endif
</div>
