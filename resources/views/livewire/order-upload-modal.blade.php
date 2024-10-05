<div class="p-6">
    <span class="text-[#222222] text-lg font-semibold mb-4 mt-6">Carica Immagini</span>
    <!-- Drag and Drop Container -->
    <form wire:submit.prevent="uploadImages" enctype="multipart/form-data">
        <div class="flex h-72 mb-8 mt-4">
            <div id="drop-area"
                class="h-full border-2 border-dashed border-[#DDDDDD] bg-[#FAFAFA] w-2/3 p-10 flex flex-col place-content-center place-items-center text-center rounded-md">
                <p class="text-[#222222] font-medium mb-2">Trascina le foto oppure selezionale dal </p>
                <button type="button" id="file-picker-btn" class="text-[#4453A5] underline">browse</button>
                <input wire:model.live="images" id="images" type="file" name="images[]" multiple class="hidden">
            </div>
            <div wire:ignore id="file-preview" class="ml-4 w-1/3 h-full overflow-y-auto"></div>
        </div>
        <div class="text-end h-8 mt-10">
            <button type="button" wire:click="$dispatch('closeModal')"
                class="mr-4 py-2 px-4 bg-[#E8E8E8] text-[#222222] rounded-md text-sm">Annulla</button>
            <button type="submit" class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm">Conferma</button>
        </div>
    </form>
</div>
@assets
    <script>
        setTimeout(() => {
            const dropArea = document.getElementById('drop-area');
            const imageInput = document.getElementById('images');
            const filePickerBtn = document.getElementById('file-picker-btn');
            const filePreview = document.getElementById('file-preview');

            let selectedFiles = []; // Store selected files here

            // Common function to prevent default drag behaviors
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            // Apply drag events to the drop area
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop area when dragging files over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => dropArea.classList.add('border-blue-500'),
                    false);
            });

            // Remove highlight when files are no longer being dragged over
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => dropArea.classList.remove('border-blue-500'),
                    false);
            });

            // Handle file drop events
            dropArea.addEventListener('drop', handleDrop, false);

            // Handle file selection via the browse button
            filePickerBtn.addEventListener('click', () => imageInput.click());

            imageInput.addEventListener('change', (e) => {
                const files = Array.from(e.target.files);
                addFiles(files);
            });

            // Handle dropped files
            function handleDrop(e) {
                const files = Array.from(e.dataTransfer.files);
                addFiles(files);
            }

            // Add files to the selection and update preview
            function addFiles(files) {
                const newFiles = filterNewFiles(files, selectedFiles); // Get new files
                if (newFiles.length > 0) {
                    selectedFiles = [...selectedFiles, ...newFiles]; // Append new files
                    updateFileInput(); // Update the input file array
                    updatePreview(); // Update preview after adding files
                    // Trigger Livewire change event only once here
                    triggerLivewireInputChange();
                }
            }

            // Filter out duplicate files based on name
            function filterNewFiles(files, existingFiles) {
                const existingFileNames = existingFiles.map(file => file.name);
                return files.filter(file => !existingFileNames.includes(file.name)); // Avoid duplicates
            }

            // Remove file by index
            function removeFile(index) {
                selectedFiles.splice(index, 1); // Remove file from the array
                updateFileInput(); // Update the input file array
                updatePreview(); // Update preview after removing files
                // Trigger Livewire change event only once here
                triggerLivewireInputChange();
            }

            // Update the input element with the currently selected files
            function updateFileInput() {
                const dataTransfer = new DataTransfer(); // Create a new DataTransfer object
                selectedFiles.forEach(file => dataTransfer.items.add(file)); // Add files to DataTransfer
                imageInput.files = dataTransfer.files; // Update the input
            }

            // Update file preview
            function updatePreview() {
                filePreview.innerHTML = ''; // Clear previous previews

                selectedFiles.forEach((file, index) => {
                    const fileElement = document.createElement('div');
                    fileElement.classList.add('flex', 'justify-between', 'items-center', 'mt-2', 'mr-4');

                    const fileName = document.createElement('p');
                    fileName.classList.add('text-sm', 'text-[#222222]', 'border-b', 'py-2');
                    fileName.textContent = `${file.name} (${formatFileSize(file.size)})`;

                    const removeButton = document.createElement('button');
                    removeButton.textContent = 'X';
                    removeButton.classList.add('text-red-500', 'ml-2');
                    removeButton.addEventListener('click', () => removeFile(index));

                    fileElement.appendChild(fileName);
                    fileElement.appendChild(removeButton);

                    filePreview.appendChild(fileElement);
                });
            }

            // Trigger Livewire input change
            function triggerLivewireInputChange() {
                // Use Livewire's method to trigger a change event
                const event = new Event('change', {
                    bubbles: true
                });
                imageInput.dispatchEvent(event);
            }

            // Helper function to format file size
            function formatFileSize(size) {
                const units = ['bytes', 'KB', 'MB', 'GB', 'TB'];
                let unitIndex = 0;

                while (size >= 1024 && unitIndex < units.length - 1) {
                    size /= 1024;
                    unitIndex++;
                }

                return `${size.toFixed(2)} ${units[unitIndex]}`;
            }
        });
    </script>
@endassets
