<div class="bg-white p-6 rounded-lg shadow-md w-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-[#222222] mb-0">
            {{ $event ? 'Modifica Evento' : 'Nuovo Evento' }}
        </h3>
        <button wire:click="$dispatch('closeModal')" class="text-gray-500 hover:text-[#9F9F9F] text-3xl">
            &times;
        </button>
    </div>

    <form wire:submit.prevent="{{ $event ? 'updateEvent' : 'createEvent' }}">
        <div class="grid grid-cols-2 gap-4">
            <!-- Name Field -->
            <div class="mb-4">
                <label class="block text-sm text-[#9F9F9F] text-[13px]">Nome evento</label>
                <input wire:model="name" required type="text" name="name"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
            </div>

            <!-- Date Field -->
            <div>
                <label class="block text-sm text-[#9F9F9F] text-[13px] mb-1">Data</label>
                <div class="relative flex items-center">
                    <!-- Date input field -->
                    <input wire:model='date' required type="date" id="date" name="date"
                        class="block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none focus:ring-0"
                        placeholder="GG/MM/AA">
                    <div
                        class="absolute top-0 bottom-0 right-0 flex justify-center place-items-center pointer-events-none bg-[#F2F1FB] w-10 rounded-r">
                        <img src="{{ asset('images/calendar icon.svg') }}" class="w-4 h-4 cursor-pointer"
                            alt="calendar icon">
                    </div>
                </div>
            </div>

            <!-- Start Time Field -->
            <div class="mb-4">
                <label class="block text-sm text-[#9F9F9F] text-[13px]">Ora inizio</label>
                <input wire:model="start_time" required type="time" name="start_time"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
            </div>

            <!-- End Time Field -->
            <div class="mb-4">
                <label class="block text-sm text-[#9F9F9F] text-[13px]">Ora fine</label>
                <input wire:model="end_time" required type="time" name="end_time"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
            </div>
        </div>

        <div class="mb-4">
            <label for="mechanic" class="block text-sm text-[#9F9F9F] text-[13px]">Aggiungi invitati</label>
            <select id="mechanic"
                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <option value="">Seleziona Tecnici</option>
                @foreach ($mechanics as $mechanic)
                    <option value="{{ $mechanic->id }}">
                        {{ $mechanic->user->name }} {{ $mechanic->surname }}
                    </option>
                @endforeach
            </select>
            <span id="mechanic-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>

            <!-- Display selected mechanics -->
            <div wire:ignore id="selectedMechanics" class="flex flex-col">
                <!-- Added mechanics will appear here -->
            </div>
        </div>

        <div class="flex place-items-center">
            <input type="checkbox" name="aluminium"
                class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"><span
                class="text-[15px] text-[#222222] ml-2">Ricordamelo 10 minuti prima</span>
        </div>

        <!-- Submit Button -->
        <div class="mt-4 text-end">
            <button type="submit" class="bg-[#1E1B58] text-white px-4 py-2 rounded-md focus:outline-none">
                {{ $event ? 'Aggiorna' : 'Conferma' }}
            </button>
        </div>
    </form>
</div>
@assets
    <script>
        setTimeout(() => {

            const mechanicSelect = document.getElementById('mechanic');
            const selectedMechanicsContainer = document.getElementById('selectedMechanics');
            const mechanicError = document.getElementById('mechanic-error');
            let selectedMechanics = [];

            mechanicSelect.addEventListener('change', function() {
                const selectedOption = mechanicSelect.options[mechanicSelect.selectedIndex];
                const mechanicName = selectedOption.textContent;
                const mechanicId = selectedOption.value;

                if (mechanicId && !selectedMechanics.includes(mechanicId)) {
                    selectedMechanics.push(mechanicId);
                    addMechanicToList(mechanicId, mechanicName);

                    mechanicSelect.value = '';
                } else if (mechanicId && selectedMechanics.includes(mechanicId)) {
                    return;
                }
            });

            function addMechanicToList(id, name) {
                const mechanicItem = document.createElement('span');
                mechanicItem.classList.add('w-max', 'bg-[#F2F1FB]', 'text-[#4453A5]', 'font-semibold', 'rounded-md',
                    'px-2', 'py-1.5', 'text-[13px]', 'mt-2');
                mechanicItem.setAttribute('data-id', id);
                mechanicItem.innerHTML = `${name} <span class="cursor-pointer">&times;</span>`;

                // Append mechanic display to the container
                selectedMechanicsContainer.appendChild(mechanicItem);
                Livewire.dispatch('addMechanic', {
                    id: id
                });
                console.log(selectedMechanics);
                // Create hidden input field to pass the selected mechanic ID
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'selectedMechanics[]'; // Use square brackets to indicate an array
                hiddenInput.value = id;
                hiddenInput.setAttribute('data-id', id);

                // Append hidden input to the form
                selectedMechanicsContainer.appendChild(hiddenInput);
                // Remove mechanic when clicking the &times;
                mechanicItem.querySelector('span.cursor-pointer').addEventListener('click', function() {
                    removeMechanic(id, mechanicItem, hiddenInput);
                });
            }


            function removeMechanic(id, element, hiddenInput) {
                selectedMechanics = selectedMechanics.filter(mechanicId => mechanicId !== id);
                selectedMechanicsContainer.removeChild(element);
                selectedMechanicsContainer.removeChild(hiddenInput);
                Livewire.dispatch('removeMechanic', {
                    id: id
                });
                mechanicSelect.value = '';
            }

            document.querySelector('form').addEventListener('submit', function(event) {
                if (selectedMechanics.length === 0) {
                    mechanicError.classList.remove('hidden');
                    event.preventDefault();
                } else {
                    mechanicError.classList.add('hidden');
                }
            });
        }, 0);
    </script>
@endassets
