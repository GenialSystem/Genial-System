<div class="mt-4">
    <h3 class="text-[#222222] text-2xl font-semibold mb-4">Postazioni</h3>
    <div class="bg-white p-4">

        <div class="mb-4 flex justify-between h-8">
            <input type="text" class="p-2 border border-gray-300 rounded" placeholder="Cerca elemento..."
                wire:model.debounce.300ms="searchTerm" />
            <div class="flex">
                <button id="openCreateModal" class="ml-4 py-1 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-full">+
                    Crea
                    nuova
                    postazione lavoro</button>
            </div>
        </div>

        <div class="overflow-x-auto rounded-md">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-[#F5F5F5]">
                    <tr class="w-full text-left text-gray-600 text-sm leading-normal">
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Cliente</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Responsabile</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Città</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Indirizzo</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N. Tecnici</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">N. Auto Assegnate</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto Riparate</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto in Lavorazione</th>
                        <th class="py-3 px-6 text-[15px] text-[#808080] font-light">Auto in coda</th>
                        <th class="py-3 px-6"></th>
                    </tr>
                </thead>
                <tbody class="text-sm text-[#222222]">
                    @forelse($rows as $row)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">

                            <td class="py-3 px-6">{{ $row->customer->name }}</td>
                            <td class="py-3 px-6">{{ $row->customer->admin_name }}</td>
                            <td class="py-3 px-6">{{ $row->customer->user->city }}</td>
                            <td class="py-3 px-6">{{ $row->customer->user->address }}</td>
                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEEDFA] text-lg text-center text-[#1E1B58] place-content-center">
                                    {{ count($row->mechanics) }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEEDFA] text-lg text-center text-[#1E1B58] place-content-center">
                                    {{ $row->customer->assigned_cars_count }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#68C9BB] text-lg text-center text-white place-content-center">
                                    {{ $row->mechanics->sum('repaired_count') }}
                                </div>
                            </td>
                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEEDFA] text-lg text-center text-[#805ECC] place-content-center">
                                    {{ $row->mechanics->sum('working_count') }}
                                </div>
                            </td>

                            <td class="py-3 px-6">
                                <div
                                    class="w-8 h-8 rounded-full bg-[#EEEDFA] text-lg text-center text-[#1E1B58] place-content-center">
                                    {{ $row->customer->queued_cars_count }}
                                </div>
                            </td>
                            <td class="py-4 px-6 flex space-x-2">
                                @livewire('show-button', ['modelId' => $row->id, 'modelClass' => \App\Models\Workstation::class], key(str()->random(10)))
                                @livewire('edit-button', ['modelId' => $row->id, 'modelName' => 'workstation', 'modelClass' => \App\Models\Workstation::class], key(str()->random(10)))
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-3 px-6 text-center">Nessun risultato</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4">
                {{ $rows->links() }}
            </div>
        </div>
    </div>
    <div id="modal" class="fixed z-50 inset-0 bg-[#707070] bg-opacity-50 justify-center items-center hidden">
        <div class="relative mx-auto my-20 p-6 bg-white w-[680px] rounded-md shadow-sm">
            <!-- Close button -->
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-[22px] font-semibold">Crea nuova postazione lavoro</h2>
                <button id="closeModal" class="text-gray-500 hover:text-[#9F9F9F] text-3xl">
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
                                <option value="{{ $customer->user_id }}"
                                    data-admin-name="{{ $customer->admin_name }}">
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
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                            readonly>
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
    </div>


</div>
<script>
    const customerSelect = document.getElementById('customer');
    const adminNameInput = document.getElementById('admin_name');
    const mechanicSelect = document.getElementById('mechanic');
    const selectedMechanicsContainer = document.getElementById('selectedMechanics');
    const mechanicError = document.getElementById('mechanic-error');
    let selectedMechanics = [];

    document.getElementById('openCreateModal').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default button action
        document.getElementById('modal').classList.remove('hidden');
    });

    // Function to close the modal
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('modal').classList.add('hidden');
    });

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
        mechanicItem.classList.add('flex', 'place-items-center', 'h-[70%]', 'bg-[#F2F1FB]', 'text-[#4453A5]',
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
        mechanicItem.querySelector('span.cursor-pointer').addEventListener('click', function(event) {
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
</script>
