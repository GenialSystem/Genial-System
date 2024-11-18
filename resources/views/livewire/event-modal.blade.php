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
                    <div onclick="document.getElementById('date').showPicker();"
                        class="absolute top-0 bottom-0 right-0 flex justify-center place-items-center bg-[#F2F1FB] w-10 rounded-r cursor-pointer"
                        id="calendar-icon">
                        <img src="{{ asset('images/calendar icon.svg') }}" class="w-4 h-4" alt="calendar icon">
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
                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                wire:change="addMechanic($event.target.value)">
                <option value="">Seleziona Tecnici</option>
                @foreach ($mechanics as $mechanic)
                    <option value="{{ $mechanic->id }}">
                        {{ $mechanic->user->name }} {{ $mechanic->user->surname }}
                    </option>
                @endforeach
            </select>
            <span id="mechanic-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>

            <!-- Display selected mechanics -->
            <div id="selectedMechanics" class="flex flex-wrap gap-2">

                @foreach ($selectedMechanics as $selected)
                    <div
                        class="w-max bg-[#F2F1FB] text-[#4453A5] font-semibold rounded-md
                     px-2 py-1.5 text-[13px] mt-2">
                        {{ $mechanics->find($selected)->user->name }} {{ $mechanics->find($selected)->user->surname }}
                        @if ($selected != optional(Auth::user()->mechanicInfo)->id)
                            <button type="button" wire:click="removeMechanic({{ $selected }})"
                                class="ml-2 text-red-500">
                                &times;
                            </button>
                        @endif

                    </div>
                @endforeach
            </div>
        </div>


        <div class="flex place-items-center">
            <input type="checkbox" name="remind_me" wire:model='notifyMe'
                class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"><span
                class="text-[15px] text-[#222222] ml-2">Ricordamelo 10 minuti prima</span>
        </div>

        <!-- Submit Button -->
        <div class="mt-4 text-end">
            <button type="submit" class="bg-[#1E1B58] text-white px-4 py-2 rounded-md focus:outline-none">
                {{ $event ? 'Aggiorna' : 'Conferma' }}
            </button>
        </div>
        @if ($errors->any())
            <div class=" text-red-500 p-2 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
</div>
