<div class="p-6 rounded-md shadow-sm">
    <!-- Close button -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-[22px] font-semibold">Crea nuova postazione lavoro</h2>
        <button wire:click="$dispatch('closeModal')" class="text-gray-500 hover:text-[#9F9F9F] text-3xl">
            &times;
        </button>
    </div>
    <!-- Form -->
    <form id="updateOrderForm" action="{{ route('workstations.store') }}" method="POST">
        @csrf
        @method('POST')
        <div class="grid grid-cols-2 gap-4">
            <!-- Cliente -->
            <div>
                <label for="customer" class="block text-[13px] text-[#9F9F9F]">Cliente</label>
                <select required name="customer" id="customer"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                    <option value="">Seleziona un cliente</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->user_id }}" data-admin-name="{{ $customer->admin_name }}">
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
                <span id="customer-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
            </div>

            <!-- Responsabile -->
            <div>
                <label for="admin_name" class="block text-[13px] text-[#9F9F9F]">Responsabile</label>
                <input required type="text" name="admin_name" id="admin_name"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none" readonly>
                <span id="admin_name-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
            </div>

            <!-- Città -->
            <div>
                <label for="city" class="block text-[13px] text-[#9F9F9F]">Città</label>
                <input required type="text" name="city" id="city"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="city-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
            </div>

            <!-- Auto assegnate -->
            <div>
                <label for="assigned_cars" class="block text-[13px] text-[#9F9F9F]">Auto assegnate</label>
                <input required type="number" min="0" name="assigned_cars" id="assigned_cars"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="assigned_cars-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
            </div>
        </div>

        <!-- Separator -->
        <div class="border-dashed border border-[#E8E8E8] my-8"></div>

        <!-- Mechanic Select Dropdown -->
        <div>
            <label for="mechanic" class="block text-[13px] text-[#9F9F9F]">Aggiungi tecnici</label>
            <select name="mechanic" id="mechanic"
                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none z-10 relative">
                <option value="">Seleziona Tecnici</option>
                @foreach ($mechanics as $mechanic)
                    <option value="{{ $mechanic->id }}">
                        {{ $mechanic->user->name }} {{ $mechanic->surname }}
                    </option>
                @endforeach
            </select>
            <span id="mechanic-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>

            <!-- Display selected mechanics -->
            <div id="selectedMechanics" class="mt-3 flex flex-wrap space-x-2">
                <!-- Added mechanics will appear here -->
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end h-8 mt-6">
            <button type="submit" id="submitForm"
                class="ml-4 py-1 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-full">
                Conferma
            </button>
        </div>
    </form>
</div>
@assets
    <script>
        setTimeout(() => {

            console.log(document.getElementById('customer'));
            const customerSelect = document.getElementById('customer');
            const adminNameInput = document.getElementById('admin_name');
            const mechanicSelect = document.getElementById('mechanic');
            const selectedMechanicsContainer = document.getElementById('selectedMechanics');
            const mechanicError = document.getElementById('mechanic-error');
            let selectedMechanics = [];

            customerSelect.addEventListener('change', function() {
                const selectedOption = customerSelect.options[customerSelect.selectedIndex];
                const adminName = selectedOption.getAttribute('data-admin-name');
                adminNameInput.value = adminName || '';
            });

            // Add mechanic when selected
            mechanicSelect.addEventListener('change', function() {
                const selectedOption = mechanicSelect.options[mechanicSelect.selectedIndex];
                const mechanicName = selectedOption.textContent;
                const mechanicId = selectedOption.value;

                // Ensure mechanic is not already selected
                if (mechanicId && !selectedMechanics.includes(mechanicId)) {
                    selectedMechanics.push(mechanicId);
                    addMechanicToList(mechanicId, mechanicName);
                    mechanicSelect.value = ''; // Reset the select dropdown
                } else if (mechanicId && selectedMechanics.includes(mechanicId)) {
                    return;
                }
            });

            // Add mechanic to the list of selected mechanics
            function addMechanicToList(id, name) {
                // Create the display element for the selected mechanic
                const mechanicItem = document.createElement('span');
                mechanicItem.classList.add('flex', 'place-items-center', 'h-[70%]', 'bg-[#F2F1FB]',
                    'text-[#4453A5]',
                    'font-semibold', 'rounded-md', 'ml-2', 'my-auto', 'px-2', 'text-[13px]', 'mt-3');
                mechanicItem.setAttribute('data-id', id);
                mechanicItem.innerHTML = `
        ${name}
        <span class="cursor-pointer ml-2">&times;</span>
    `;

                // Append mechanic display to the container
                selectedMechanicsContainer.appendChild(mechanicItem);

                // Create hidden input field to pass the selected mechanic ID
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'mechanics[]'; // "mechanics" is an array in the request
                hiddenInput.value = id;
                hiddenInput.setAttribute('data-id', id); // To easily find it later

                // Append hidden input to the form
                selectedMechanicsContainer.appendChild(hiddenInput);

                // Remove mechanic when clicking the &times;
                mechanicItem.querySelector('span.cursor-pointer').addEventListener('click', function(
                    event) {
                    event.preventDefault();
                    removeMechanic(id, mechanicItem, hiddenInput);
                });
            }


            // Remove mechanic from the selected list
            // Remove mechanic from the selected list and remove hidden input
            function removeMechanic(id, element, hiddenInput) {
                selectedMechanics = selectedMechanics.filter(mechanicId => mechanicId !== id);
                selectedMechanicsContainer.removeChild(element);
                selectedMechanicsContainer.removeChild(hiddenInput); // Remove the hidden input as well
                mechanicSelect.value = '';
            }


            // Custom form validation
            document.getElementById('submitForm').addEventListener('click', function(event) {
                let formIsValid = true;

                // Example of required field validation (mechanic)
                if (selectedMechanics.length === 0) {
                    mechanicError.classList.remove('hidden');
                    formIsValid = false;
                } else {
                    mechanicError.classList.add('hidden');
                }

                // Additional field validation can be added here (city, customer, etc.)

                // Prevent form submission if validation fails
                if (!formIsValid) {
                    event.preventDefault();
                }
            });

        }, 0);
    </script>
@endassets
