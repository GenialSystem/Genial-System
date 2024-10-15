<div class="p-6 rounded-md shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-[22px] font-semibold">
            {{ isset($workstation) ? 'Modifica postazione lavoro' : 'Crea nuova postazione lavoro' }}
        </h2>
        <button wire:click="$dispatch('closeModal')" class="text-gray-500 hover:text-[#9F9F9F] text-3xl">
            &times;
        </button>
    </div>

    <form id="updateOrderForm"
        action="{{ isset($workstation) ? route('workstations.update', $workstation->id) : route('workstations.store') }}"
        method="POST">
        @csrf
        @if (isset($workstation))
            @method('PUT')
        @else
            @method('POST')
        @endif

        <div class="grid grid-cols-2 gap-4">
            <!-- Cliente -->
            <div>
                <label for="customer" class="block text-[13px] text-[#9F9F9F]">Cliente</label>
                <div class="relative">

                    <select id="customer" wire:model.live='selectedCustomer' required name="customer"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none ">
                        <option value="">Seleziona un cliente</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" data-admin-name="{{ $customer->admin_name }}"
                                {{ isset($workstation) && $workstation->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->user->name }} {{ $customer->user->surname }}
                            </option>
                        @endforeach
                    </select>
                    <span id="customer-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    @if ($customers->find($selectedCustomer))
                        <span
                            class="absolute inset-y-0 left-0 h-[70%] flex items-center bg-[#F2F1FB] text-[#4453A5] font-semibold rounded-md ml-2 px-4 pointer-events-none truncate"
                            style="top: 50%; transform: translateY(-50%); max-width: calc(100% - 10px); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            <!-- Adjusted width to fit within the select -->
                            {{ $customers->find($selectedCustomer)->user->name ?? 'N/A' }}
                            {{ $customers->find($selectedCustomer)->user->surname ?? 'N/A' }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Responsabile -->
            <div>
                <label for="admin_name" class="block text-[13px] text-[#9F9F9F]">Responsabile</label>
                <div class="relative">
                    <input required type="text" name="admin_name" id="admin_name"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                        readonly value="{{ isset($workstation) ? $workstation->customer->admin_name : '' }}">
                    <span id="admin_name-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    @if ($customers->find($selectedCustomer))
                        <span
                            class=" absolute inset-y-0 left-0 h-[70%] flex items-center bg-[#F2F1FB] text-[#4453A5] font-semibold rounded-md ml-2 px-4 pointer-events-none truncate"
                            style="top: 50%; transform: translateY(-50%); max-width: calc(100% - 10px); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            {{ $customers->find($selectedCustomer)->admin_name ?? 'N/A' }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Città -->
            <div>
                <label for="city" class="block text-[13px] text-[#9F9F9F]">Città</label>
                <input required type="text" name="city" id="city"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                    value="{{ isset($workstation) ? $workstation->city : '' }}">
            </div>

            <!-- Auto assegnate -->
            <div>
                <label for="assigned_cars" class="block text-[13px] text-[#9F9F9F]">Auto assegnate</label>
                <input required type="number" min="0" name="assigned_cars" id="assigned_cars"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                    value="{{ isset($workstation) ? $workstation->assigned_cars_count : '' }}">
            </div>

            <!-- Indirizzo -->
            <div>
                <label for="address" class="block text-[13px] text-[#9F9F9F]">Indirizzo</label>
                <input required type="text" name="address" id="address"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                    value="{{ isset($workstation) ? $workstation->address : '' }}">
            </div>
        </div>

        <div class="border-dashed border border-[#E8E8E8] my-8"></div>

        <!-- Mechanic Select Dropdown -->
        <div>
            <label for="mechanic" class="block text-[13px] text-[#9F9F9F]">Aggiungi tecnici</label>
            <select id="mechanic" wire:change="addMechanic($event.target.value)"
                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <option value="">Seleziona Tecnici</option>
                @foreach ($mechanics as $mechanic)
                    <option value="{{ $mechanic->id }}">
                        {{ $mechanic->user->name }} {{ $mechanic->user->surname }}
                    </option>
                @endforeach
            </select>

            <!-- Display selected mechanics -->
            <div class="mt-3 flex flex-wrap space-x-2">
                @foreach ($selectedMechanics as $mechanicId)
                    <span
                        class="flex items-center bg-[#F2F1FB] text-[#4453A5] font-semibold rounded-md ml-2 px-2 py-1 text-[13px] mt-3">
                        {{ $mechanics->find($mechanicId)->user->name }}
                        {{ $mechanics->find($mechanicId)->user->surname }}
                        <span class="cursor-pointer ml-2"
                            wire:click="removeMechanic({{ $mechanicId }})">&times;</span>
                    </span>

                    <!-- Hidden Input for Mechanics -->
                    <input type="hidden" name="mechanics[]" value="{{ $mechanicId }}">
                @endforeach
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end h-8 mt-6">
            <button type="submit" id="submitForm"
                class="ml-4 py-1 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-full">
                {{ isset($workstation) ? 'Aggiorna' : 'Conferma' }}
            </button>
        </div>
    </form>
</div>
