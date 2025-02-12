<div class="mx-auto p-4 bg-white 2xl:w-[1000px] rounded-md shadow-sm">
    @if ($errors->any())
        <div id="error-banner" class="bg-[#DC0814] text-white text-center py-2 rounded-md my-4">
            {{ $errors->first() }}
        </div>
    @endif
    <h4 class="text-[#222222] font-semibold text-xl mb-4">Crea scheda auto grandinata</h4>
    <div class="flex place-items-center space-x-4 mb-6">
        <div id="step-indicator-1"
            class="text-sm font-medium bg-[#F2F1FB] rounded-full w-8 h-8 flex items-center justify-center text-[#1E1B58] border border-[#1E1B58]">
            1
        </div>
        <span class="text-[#1E1B58] text-[15px]">Creazione riparazione</span>
        <div id="step-indicator-2"
            class="text-sm font-medium text-[#9F9F9F] bg-[#E0E0E0] rounded-full w-8 h-8 flex items-center justify-center border border-[#C3C3C3]">
            2
        </div>
        <span id="step-2-text" class="text-[#9F9F9F] text-[15px]">Scheda Tecnica</span>
        <div id="step-indicator-3"
            class="text-sm font-medium text-[#9F9F9F] bg-[#E0E0E0] rounded-full w-8 h-8 flex items-center justify-center border border-[#C3C3C3]">
            3
        </div>
        <span id="step-3-text" class="text-[#9F9F9F] text-[15px]">Foto</span>
    </div>
    <form enctype="multipart/form-data" action="{{ route('orders.store') }}" method="POST"
        class="mt-4 text-[#9F9F9F] text-[13px]">
        @csrf
        <!-- Step 1 -->
        <div id="step-1">
            <!-- Row 1 -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="id" class="block text-sm font-medium">N* riparazione</label>
                    <input disabled required type="text" name="id" id="id"
                        value="#{{ \App\Models\Order::orderBy('id', 'DESC')->first()->id + 1 ?? '' }}"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                    <span id="id-error" class="text-red-500 text-xs hidden"></span>
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium mb-1">Date</label>

                    <div class="relative flex items-center">

                        <input required type="date" id="date" name="date"
                            class="block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none focus:ring-0"
                            placeholder="GG/MM/AA">
                        <div class="absolute top-0 bottom-0 right-0 flex justify-center place-items-center bg-[#F2F1FB] w-10 rounded-r cursor-pointer"
                            onclick="document.getElementById('date').showPicker();">
                            <img src="{{ asset('images/calendar icon.svg') }}" class="w-4 h-4" alt="calendar icon">
                        </div>
                    </div>

                </div>

                <div>
                    <!-- Cliente (Customer) Select Dropdown -->
                    <label for="customer" class="block text-sm font-medium">Cliente</label>
                    <select name="customer" id="customer"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <option value="" data-admin-name="">Seleziona un cliente</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" data-admin-name="{{ $customer->admin_name }}"
                                {{ $selectedCustomerId == $customer->id ? 'selected' : '' }}>
                                {{ $customer->user->name . ' ' . $customer->user->surname }}
                            </option>
                        @endforeach
                    </select>

                    <span id="customer-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
            </div>

            <!-- Row 2 -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <!-- Responsabile (Admin Name) Text Input, will be auto-filled -->
                    <label for="admin_name" class="block text-sm font-medium">Responsabile</label>
                    <input required type="text" name="admin_name" id="admin_name"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                        disabled value="{{ $adminName }}">
                    <span id="admin_name-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
            </div>
            <div class="border-dashed border border-[#E8E8E8] my-8"></div>

            <!-- Row 3 -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <!-- Tecnico (Mechanic) Select Dropdown -->
                    <label for="mechanic" class="block text-sm font-medium">Tecnico</label>
                    <select id="mechanicSelect"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <option value="">Seleziona un tecnico</option>
                        @foreach ($mechanics as $mechanic)
                            <option value="{{ $mechanic->id }}">{{ $mechanic->user->name }}
                                {{ $mechanic->user->surname }}</option>
                        @endforeach
                    </select>

                    <!-- Hidden input to store selected mechanics' IDs -->
                    <input type="hidden" name="mechanic" id="mechanicIds" />

                    <!-- Selected mechanics list -->

                    <span id="mechanic-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
                @role('admin')
                    <div>
                        <label for="earn_mechanic_percentage" class="block text-sm font-medium">Guadagno Tecnico %</label>
                        <input required type="number" min="0" max="99" name="earn_mechanic_percentage"
                            id="earn_mechanic_percentage"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="earn_mechanic_percentage-error" class="text-red-500 text-xs hidden">Campo
                            obbligatorio.</span>
                    </div>
                <div>
                  <label for="assembly_deassembly"
                                    class="block text-sm text-[#9F9F9F] text-[13px]">Montaggio/Smontaggio</label>
                                    <select name="assembly_disassembly" id="assembly_disassembly" class="mt-1 p-2 border-transparent rounded-md w-20 bg-white">
                                    <option value="1">Si</option>
                                    <option value="0">No</option>
                                </select>
                    <span id="assembly_deassembly-error" class="text-red-500 text-xs hidden"></span>
                </div>
                @endrole
                <div>
                    <label for="color" class="block text-sm font-medium">Colore</label>
                    <input required type="text" name="color" id="color"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                    <span id="color-error" class="text-red-500 text-xs hidden">Campo
                        obbligatorio.</span>
                </div>
            </div>
            <div id="selectedMechanics" class="my-2 flex flex-wrap gap-2">
                <!-- JavaScript will append selected mechanics here -->
            </div>
            <!-- Row 4 -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="plate" class="block text-sm font-medium">Targa/Telaio</label>
                    <input required type="text" name="plate" id="plate"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                        value="{{ $plate }}">
                    <span id="plate-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
                <div>
                    <label for="brand" class="block text-sm font-medium">Marca modello</label>
                    <input required type="text" name="brand" id="brand"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                        value="{{ $brand }}">
                    <span id="brand-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
                <div>
                    <label for="price" class="block text-sm font-medium">Importo</label>
                    <input required type="number" name="price" id="price" step="0.01" min="0"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                    <span id="price-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>

            </div>

        </div>

        <!-- Step 2 -->
        <div id="step-2" class="hidden">
            <span class="text-[#222222]">Compila i seguenti dati della scheda tecnica</span>
            <div class="grid grid-cols-3 gap-4 my-6">
                @foreach ($parts as $index => $part)
                    <div>
                        <label for="part_{{ $index }}"
                            class="block text-sm font-medium">{{ $part->name }}</label>
                        <input value="0" required type="number" min="0"
                            name="parts[{{ $index }}][damage_count]" id="part_{{ $index }}"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <!-- Store only the name, not the entire object -->
                        <input type="hidden" name="parts[{{ $index }}][name]" value="{{ $part->name }}">
                        <span id="part_{{ $index }}-error" class="text-red-500 text-xs hidden"></span>
                    </div>
                @endforeach
                <div>
                    <label for="car_size" class="block text-sm font-medium">Dimensioni veicolo</label>
                    <select name="car_size" id="car_size"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <option value="">- Seleziona -</option>
                        @foreach ($car_sizes as $car_size)
                            <option value="{{ $car_size }}">{{ $car_size }}</option>
                        @endforeach
                    </select>
                    <span id="car_size-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
                <div class="flex place-items-center">
                    <input required type="checkbox" name="aluminium"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"><span
                        class="text-[15px] text-[#222222] ml-2">Alluminio</span>
                    <span id="aluminium-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
                <div>
                    <label for="damage_diameter" class="block text-sm font-medium">Diametro bolli</label>
                    <input required type="text" name="damage_diameter" id="damage_diameter"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                    <span id="damage_diameter-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
            </div>
            <label class="block text-sm font-medium" for="replacements">Ricambi</label>
            <textarea name="replacements" id="replacements" cols="20" rows="10"
                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"></textarea>

            <label class="block text-sm font-medium mt-4" for="notes">Appunti</label>
            <textarea name="notes" id="notes" cols="10" rows="10"
                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"></textarea>
        </div>

        <div id="step-3" class="hidden">
            <span class="text-[#222222] text-[15px] mb-4">Carica le foto dell'auto</span>
            <div id="file-type-error" class="text-[#DC0814] text-lg my-3"></div>
            <!-- Drag and Drop Container -->
            <div class="flex h-72 mb-8 mt-4">
                <div id="drop-area"
                    class="h-full border-2 border-dashed border-[#DDDDDD] bg-[#FAFAFA] w-2/3 p-10 flex flex-col place-content-center place-items-center text-center rounded-md cursor-pointer">
                    <p class="text-[#222222] font-medium mb-2">Trascina le foto oppure selezionale dal </p>
                    <button type="button" id="file-picker-btn" class="text-[#4453A5] underline">browse</button>
                    <input accept="image/*" id="images" type="file" name="images[]" multiple class="hidden">
                </div>
                <div id="file-preview" class="ml-4 w-1/3 h-full overflow-y-auto"></div>
            </div>

            <span id="images-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>

            <span class="text-[#222222] text-[15px]">Carica le foto dello smontaggio</span>
            <div class="flex h-72 mt-4">
                <div id="drop-area-disassembly"
                    class="h-full border-2 border-dashed border-[#DDDDDD] bg-[#FAFAFA] w-2/3 p-10 flex flex-col place-content-center place-items-center text-center rounded-md cursor-pointer">
                    <p class="text-[#222222] font-medium mb-2">Trascina le foto oppure selezionale dal </p>
                    <button type="button" id="file-picker-btn-disassembly"
                        class="text-[#4453A5] underline">browse</button>
                    <input accept="image/*" id="images-disassembly" type="file" name="images-disassembly[]"
                        multiple class="hidden">
                </div>
                <div id="file-preview-disassembly" class="ml-4 w-1/3 h-full overflow-y-auto"></div>
            </div>
        </div>


        <div class="text-end h-8 mt-10">
            <button type="button" id="prev-step"
                class="mr-4 py-2 px-4 bg-[#E8E8E8] text-[#222222] rounded-md text-sm hidden">Annulla</button>
            <button type="button" id="next-step"
                class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm">Conferma</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customerSelect = document.getElementById('customer');
        const adminNameInput = document.getElementById('admin_name');
        const nextStepButton = document.getElementById('next-step');
        const prevStepButton = document.getElementById('prev-step');
        const step1 = document.getElementById('step-1');
        const step2 = document.getElementById('step-2');
        const step3 = document.getElementById('step-3');
        const stepIndicators = [
            document.getElementById('step-indicator-1'),
            document.getElementById('step-indicator-2'),
            document.getElementById('step-indicator-3')
        ];
        const stepTexts = [
            document.getElementById('step-2-text'),
            document.getElementById('step-3-text')
        ];
        const form = document.querySelector('form');
        let stepCounter = 1;

        // Function to validate fields
        function validateFields(requiredFields) {
            let valid = true;
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                const errorSpan = document.getElementById(`${field}-error`);
                if (input && !input.value.trim()) {
                    input.classList.add('border-red-500');
                    if (errorSpan) errorSpan.classList.remove('hidden');
                    valid = false;
                } else {
                    if (input) input.classList.remove('border-red-500');
                    if (errorSpan) errorSpan.classList.add('hidden');
                }
            });
            return valid;
        }

        // Function to validate part inputs
        function validateParts() {
            let valid = true;
            document.querySelectorAll('input[name^="parts"]').forEach(function(partInput) {
                const errorSpan = document.getElementById(`${partInput.id}-error`);
                if (!partInput.value.trim()) {
                    partInput.classList.add('border-red-500');
                    if (errorSpan) errorSpan.classList.remove('hidden');
                    valid = false;
                } else {
                    partInput.classList.remove('border-red-500');
                    if (errorSpan) errorSpan.classList.add('hidden');
                }
            });
            return valid;
        }

        // Event listener for customer selection change
        customerSelect.addEventListener('change', function() {
            const selectedOption = customerSelect.options[customerSelect.selectedIndex];
            const adminName = selectedOption.getAttribute('data-admin-name');
            adminNameInput.value = adminName || '';
        });

        // Handle Next Step
        nextStepButton.addEventListener('click', function(event) {
            let valid = true;

            if (stepCounter === 1) {
                const requiredFieldsStep1 = ['earn_mechanic_percentage', 'color', 'date', 'customer',
                    'mechanicIds',
                    'admin_name', 'brand', 'plate', 'price'
                ];
                valid = validateFields(requiredFieldsStep1);

                if (valid) {
                    updateStep(1, 2);
                    stepCounter++;
                }
            } else if (stepCounter === 2) {
                const requiredFieldsStep2 = ['replacements', 'notes', 'car_size', 'damage_diameter'];
                valid = validateParts() && validateFields(requiredFieldsStep2);

                if (valid) {
                    updateStep(2, 3);
                    stepCounter++;
                }
            } else if (stepCounter === 3) {
                if (valid) {
                    form.submit();
                }
            }
        });

        // Handle Previous Step
        prevStepButton.addEventListener('click', function() {
            if (stepCounter === 2) {
                updateStep(2, 1, true); // Move back to Step 1
                stepCounter--;
            } else if (stepCounter === 3) {
                updateStep(3, 2, true); // Move back to Step 2
                stepCounter--;
            }

            // Hide prev button when returning to the first step
            if (stepCounter === 1) {
                prevStepButton.classList.add('hidden');
            }
        });

        // Function to update steps and indicators
        function updateStep(currentStep, nextStep, isPrev = false) {
            // Hide current step and show next step
            document.getElementById(`step-${currentStep}`).classList.add('hidden');
            document.getElementById(`step-${nextStep}`).classList.remove('hidden');

            if (!isPrev) {
                // Move forward: Activate the next step and deactivate the current step
                stepIndicators[currentStep - 1].classList.replace('bg-[#F2F1FB]', 'bg-[#1E1B58]');
                stepIndicators[currentStep - 1].classList.replace('text-[#1E1B58]', 'text-white');
                stepIndicators[nextStep - 1].classList.replace('bg-[#E0E0E0]', 'bg-[#F2F1FB]');
                stepIndicators[nextStep - 1].classList.replace('border-[#C3C3C3]', 'border-[#1E1B58]');
                stepIndicators[nextStep - 1].classList.replace('text-[#9F9F9F]', 'text-[#1E1B58]');
                if (stepTexts[nextStep - 2]) {
                    stepTexts[nextStep - 2].classList.replace('text-[#9F9F9F]', 'text-[#1E1B58]');
                }
            } else {
                // Move backward: Deactivate current step and reactivate the previous step
                stepIndicators[currentStep - 1].classList.replace('bg-[#1E1B58]', 'bg-[#F2F1FB]');
                stepIndicators[currentStep - 1].classList.replace('text-white', 'text-[#1E1B58]');
                stepIndicators[nextStep - 1].classList.replace('bg-[#F2F1FB]', 'bg-[#E0E0E0]');
                stepIndicators[nextStep - 1].classList.replace('border-[#1E1B58]', 'border-[#C3C3C3]');
                stepIndicators[nextStep - 1].classList.replace('text-[#1E1B58]', 'text-[#9F9F9F]');
                if (stepTexts[currentStep - 2]) {
                    stepTexts[currentStep - 2].classList.replace('text-[#1E1B58]', 'text-[#9F9F9F]');
                }
            }

            // Show prev button if not on the first step
            if (nextStep > 1) {
                prevStepButton.classList.remove('hidden');
            }
        }

        const mechanicSelect = document.getElementById('mechanicSelect');
        const mechanicIdsInput = document.getElementById('mechanicIds');
        const selectedMechanicsDiv = document.getElementById('selectedMechanics');

        // Array to keep track of selected mechanics
        let selectedMechanics = [];
        Livewire.on('default_mechanic', function(data) {
            // Access the first object in the array
            const mechanic = data[0];

            selectedMechanics.push({
                id: mechanic.mechanic_id, // Use `mechanic_id` here
                name: mechanic.name,
                surname: mechanic.surname
            });

            // Render the updated list
            renderSelectedMechanics();
        });

        // Function to render   the selected mechanics
        function renderSelectedMechanics() {
            // Clear the current displayed list
            selectedMechanicsDiv.innerHTML = '';

            // Loop through the selected mechanics and create span elements
            selectedMechanics.forEach(mechanic => {
                const mechanicSpan = document.createElement('span');
                mechanicSpan.classList.add('w-max', 'bg-[#F2F1FB]', 'text-[#4453A5]', 'font-semibold',
                    'rounded-md',
                    'px-2', 'py-1.5', 'text-[13px]', 'mt-3', 'mb-6');

                // Mechanic name and delete button
                mechanicSpan.innerHTML = `
                ${mechanic.name} ${mechanic.surname}
                <button type="button" class="ml-2 text-[#4453A5]" onclick="removeMechanic(${mechanic.id})">&times;</button>
            `;
                selectedMechanicsDiv.appendChild(mechanicSpan);
            });

            // Update the hidden input with the selected mechanic IDs
            mechanicIdsInput.value = selectedMechanics.map(mechanic => mechanic.id).join(',');
        }

        // Function to add a mechanic when selected from the dropdown
        mechanicSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];

            // Get mechanic details from the option element
            const mechanicId = selectedOption.value; // This should be a string
            const mechanicName = selectedOption.text.split(' ')[0];
            const mechanicSurname = selectedOption.text.split(' ')[1];

            // Check if mechanic is already selected
            if (!selectedMechanics.some(mechanic => mechanic.id === mechanicId) && mechanicId) {
                // Add mechanic to the selectedMechanics array
                selectedMechanics.push({
                    id: mechanicId, // Keep this as a string
                    name: mechanicName,
                    surname: mechanicSurname
                });

                // Render the updated list
                renderSelectedMechanics();
            }

            // Reset the select dropdown
            mechanicSelect.value = '';
        });

        // Function to remove a mechanic from the selected list
        window.removeMechanic = function(mechanicId) {
            // Ensure the ID is treated as a string
            mechanicId = String(mechanicId); // Convert mechanicId to string for consistent comparison
            // Remove the mechanic from the selectedMechanics array
            selectedMechanics = selectedMechanics.filter(mechanic => mechanic.id !== mechanicId);
            // Re-render the list
            renderSelectedMechanics();
        };

        // For the car photos section
        const dropAreaCar = document.getElementById('drop-area');
        const imageInputCar = document.getElementById('images');
        const filePickerBtnCar = document.getElementById('file-picker-btn');
        const filePreviewCar = document.getElementById('file-preview');

        let selectedFilesCar = []; // Store selected car files here

        // For the disassembly photos section
        const dropAreaDisassembly = document.getElementById('drop-area-disassembly');
        const imageInputDisassembly = document.getElementById('images-disassembly');
        const filePickerBtnDisassembly = document.getElementById('file-picker-btn-disassembly');
        const filePreviewDisassembly = document.getElementById('file-preview-disassembly');

        let selectedFilesDisassembly = []; // Store selected disassembly files here

        // Common function to prevent default drag behaviors
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Apply drag events to both areas
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropAreaCar.addEventListener(eventName, preventDefaults, false);
            dropAreaDisassembly.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area when dragging files over it for both sections
        ['dragenter', 'dragover'].forEach(eventName => {
            dropAreaCar.addEventListener(eventName, () => dropAreaCar.classList.add('border-blue-500'),
                false);
            dropAreaDisassembly.addEventListener(eventName, () => dropAreaDisassembly.classList.add(
                'border-blue-500'), false);
        });

        // Remove highlight when files are no longer being dragged over
        ['dragleave', 'drop'].forEach(eventName => {
            dropAreaCar.addEventListener(eventName, () => dropAreaCar.classList.remove(
                'border-blue-500'), false);
            dropAreaDisassembly.addEventListener(eventName, () => dropAreaDisassembly.classList.remove(
                'border-blue-500'), false);
        });

        // Handle file drop events for both sections
        dropAreaCar.addEventListener('drop', handleDropCar, false);
        dropAreaDisassembly.addEventListener('drop', handleDropDisassembly, false);

        function handleDropCar(e) {
            const files = [...e.dataTransfer.files];
            addFiles(files, 'car');
        }

        function handleDropDisassembly(e) {
            const files = [...e.dataTransfer.files];
            addFiles(files, 'disassembly');
        }

        // Handle files selected via input (for both sections)
        filePickerBtnCar.addEventListener('click', function() {
            imageInputCar.click();
        });

        filePickerBtnDisassembly.addEventListener('click', function() {
            imageInputDisassembly.click();
        });

        imageInputCar.addEventListener('change', function(e) {
            const files = [...e.target.files];
            addFiles(files, 'car');
        });

        imageInputDisassembly.addEventListener('change', function(e) {
            const files = [...e.target.files];
            addFiles(files, 'disassembly');
        });
        const fileTypeError = document.getElementById('file-type-error');
        // Add files to the selection and update preview
        function addFiles(files, type) {
            const validImageFiles = files.filter(isImage); // Filter files to only include images

            // If no valid images, notify the user
            if (validImageFiles.length === 0) {
                fileTypeError.textContent = 'Seleziona soltanto immagini';
                return; // Exit the function if no image files are present
            }

            // Notify about invalid files
            const invalidFiles = files.filter(file => !isImage(file));
            if (invalidFiles.length > 0) {
                fileTypeError.textContent =
                    `Seleziona soltanto immagini, rimuovi le seguenti: ${invalidFiles.map(file => file.name).join(', ')}`;
                return;
            }

            if (type === 'car') {
                selectedFilesCar = [...selectedFilesCar, ...filterNewFiles(files,
                    selectedFilesCar)]; // Append new files
                updateFileInput('car');
                updatePreview('car');
            } else if (type === 'disassembly') {
                selectedFilesDisassembly = [...selectedFilesDisassembly, ...filterNewFiles(files,
                    selectedFilesDisassembly)]; // Append new files
                updateFileInput('disassembly');
                updatePreview('disassembly');
            }
        }

        // Helper function to filter out duplicate files
        function filterNewFiles(files, existingFiles) {
            const existingFileNames = existingFiles.map(file => file.name);
            return files.filter(file => !existingFileNames.includes(file
                .name)); // Avoid duplicates based on file name
        }

        // Remove file by index and type
        function removeFile(index, type) {
            if (type === 'car') {
                selectedFilesCar.splice(index, 1); // Remove file from car array
                updateFileInput('car');
                updatePreview('car');
            } else if (type === 'disassembly') {
                selectedFilesDisassembly.splice(index, 1); // Remove file from disassembly array
                updateFileInput('disassembly');
                updatePreview('disassembly');
            }
        }

        // Update the input element with the currently selected files
        function updateFileInput(type) {
            const dataTransfer = new DataTransfer(); // Create a new DataTransfer object

            if (type === 'car') {
                selectedFilesCar.forEach(file => dataTransfer.items.add(file)); // Add car files to DataTransfer
                imageInputCar.files = dataTransfer.files; // Update the car file input
            } else if (type === 'disassembly') {
                selectedFilesDisassembly.forEach(file => dataTransfer.items.add(
                    file)); // Add disassembly files to DataTransfer
                imageInputDisassembly.files = dataTransfer.files; // Update the disassembly file input
            }
        }

        function isImage(file) {
            return file && file['type'].startsWith('image/');
        }

        // Update file preview for both sections
        function updatePreview(type) {
            const filePreview = type === 'car' ? filePreviewCar : filePreviewDisassembly;
            const selectedFiles = type === 'car' ? selectedFilesCar : selectedFilesDisassembly;

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
                removeButton.addEventListener('click', () => removeFile(index, type));

                fileElement.appendChild(fileName);
                fileElement.appendChild(removeButton);

                filePreview.appendChild(fileElement);
            });
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


        // Handle Previous Step
        prevStepButton.addEventListener('click', function(event) {
            if (stepCounter === 2) {
                stepIndicators[1].classList.replace('bg-[#1E1B58]', 'bg-[#E0E0E0]');
                stepIndicators[1].classList.replace('text-white', 'text-[#9F9F9F]');
                stepTexts[0].classList.replace('text-[#1E1B58]', 'text-[#9F9F9F]');
                step2.classList.add('hidden');
                step1.classList.remove('hidden');
                prevStepButton.classList.add('hidden');
                stepCounter--;
            } else if (stepCounter === 3) {
                stepIndicators[2].classList.replace('bg-[#1E1B58]', 'bg-[#E0E0E0]');
                stepIndicators[2].classList.replace('text-white', 'text-[#9F9F9F]');
                stepTexts[1].classList.replace('text-[#1E1B58]', 'text-[#9F9F9F]');
                step3.classList.add('hidden');
                step2.classList.remove('hidden');
                stepCounter--;
            }
        });
    });
</script>
