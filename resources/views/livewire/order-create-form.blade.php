<div class="mx-auto p-4 bg-white w-[1000px] rounded-md shadow-sm">
    @if ($errors->any())
        <div id="error-banner" class="bg-red-500 text-white text-center py-2 rounded-md my-4">
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
            class="text-sm font-medium text-[#9F9F9F] bg-[#E0E0E0] rounded-full w-8 h-8 flex items-center justify-center">
            2
        </div>
        <span id="step-2-text" class="text-[#9F9F9F] text-[15px]">Scheda Tecnica</span>
        <div id="step-indicator-3"
            class="text-sm font-medium text-[#9F9F9F] bg-[#E0E0E0] rounded-full w-8 h-8 flex items-center justify-center">
            3
        </div>
        <span id="step-3-text" class="text-[#9F9F9F] text-[15px]">Foto</span>
    </div>
    <form action="{{ route('orders.store') }}" method="POST" class="mt-4 text-[#9F9F9F] text-[13px]">
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
                    <label for="date" class="block text-sm font-medium">Date</label>
                    <div class="relative flex items-center">
                        <!-- Date input field -->
                        <input required type="date" id="date" name="date"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none focus:ring-0"
                            placeholder="GG/MM/AA">

                        <!-- Calendar icon -->
                        <div
                            class="absolute right-0 flex justify-center place-items-center pointer-events-none bg-[#F2F1FB] w-10 rounded-r h-full">
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
                            <option value="{{ $customer->id }}" data-admin-name="{{ $customer->admin_name }}">
                                {{ $customer->name }}</option>
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
                        readonly>
                    <span id="admin_name-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
            </div>
            <div class="border-dashed border border-[#E8E8E8] my-8"></div>

            <!-- Row 3 -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <!-- Tecnico (Mechanic) Select Dropdown -->
                    <label for="mechanic" class="block text-sm font-medium">Tecnico</label>
                    <select name="mechanic" id="mechanic"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <option value="">Seleziona un tecnico</option>
                        @foreach ($mechanics as $mechanic)
                            <option value="{{ $mechanic->id }}">{{ $mechanic->user->name }}</option>
                        @endforeach
                    </select>
                    <span id="mechanic-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
                <div>
                    <label for="earn_mechanic_percentage" class="block text-sm font-medium">Guadagno Tecnico %</label>
                    <input required type="number" min="1" max="99" name="earn_mechanic_percentage"
                        id="earn_mechanic_percentage"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                    <span id="earn_mechanic_percentage-error" class="text-red-500 text-xs hidden">Campo
                        obbligatorio.</span>
                </div>

            </div>

            <!-- Row 4 -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="plate" class="block text-sm font-medium">Targa/Telaio</label>
                    <input required type="text" name="plate" id="plate"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                    <span id="plate-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
                <div>
                    <label for="brand" class="block text-sm font-medium">Marca modello</label>
                    <input required type="text" name="brand" id="brand"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
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

        <!-- Step 2 -->
        <div id="step-2" class="hidden">
            <span class="text-[#222222]">Compila i seguenti dati della scheda tecnica</span>
            <div class="grid grid-cols-3 gap-4 my-6">
                @foreach ($parts as $index => $part)
                    <div>
                        <label for="part_{{ $index }}"
                            class="block text-sm font-medium">{{ $part->name }}</label>
                        <input required type="number" min="0"
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
            </div>
            <label class="block text-sm font-medium" for="replacements">Ricambi</label>
            <textarea name="replacements" id="replacements" cols="20" rows="10"
                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"></textarea>

            <label class="block text-sm font-medium mt-4" for="notes">Appunti</label>
            <textarea name="notes" id="notes" cols="10" rows="10"
                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"></textarea>
        </div>


        <div id="step-3" class="hidden">
            3
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

        // New function to validate part inputs
        function validateParts() {
            let valid = true;

            // Get all part input fields using a querySelectorAll and loop through them
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
            adminNameInput.value = adminName || ''; // If no admin_name, set to empty string
        });

        // Handle Next Step
        nextStepButton.addEventListener('click', function(event) {
            let valid = true;

            if (stepCounter === 1) {
                const requiredFieldsStep1 = ['earn_mechanic_percentage', 'date', 'customer', 'mechanic',
                    'admin_name', 'brand', 'plate', 'price'
                ];
                valid = validateFields(requiredFieldsStep1);

                if (valid) {
                    stepIndicators[1].classList.replace('bg-[#E0E0E0]', 'bg-[#1E1B58]');
                    stepIndicators[1].classList.replace('text-[#9F9F9F]', 'text-white');
                    stepTexts[0].classList.replace('text-[#9F9F9F]', 'text-[#1E1B58]');
                    step1.classList.add('hidden');
                    step2.classList.remove('hidden');
                    prevStepButton.classList.remove('hidden');
                    stepCounter++;
                }
            } else if (stepCounter === 2) {
                const requiredFieldsStep2 = ['replacements', 'car_size']; // Ricambi field
                valid = validateParts() && validateFields(requiredFieldsStep2);

                if (valid) {
                    stepIndicators[2].classList.replace('bg-[#E0E0E0]', 'bg-[#1E1B58]');
                    stepIndicators[2].classList.replace('text-[#9F9F9F]', 'text-white');
                    stepTexts[1].classList.replace('text-[#9F9F9F]', 'text-[#1E1B58]');
                    step2.classList.add('hidden');
                    step3.classList.remove('hidden');
                    stepCounter++;
                }
            } else if (stepCounter === 3) {
                form.submit();
            }
        });

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
