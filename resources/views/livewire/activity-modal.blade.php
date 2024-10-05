<div class="bg-white p-6 rounded-lg shadow-md w-full">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-[#222222] mb-0">
            Aggiungi attività
        </h3>
        <button wire:click="$dispatch('closeModal')" class="text-gray-500 hover:text-[#9F9F9F] text-3xl">
            &times;
        </button>
    </div>
    <form wire:submit.prevent="submit">

        <div class="w-1/2 mb-3">
            <label for="date" class="block text-[13px] text-[#9F9F9F] mb-1">Date</label>
            <div class="relative flex items-center">
                <input required type="date" id="date" wire:model="date"
                    class="block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none focus:ring-0"
                    placeholder="GG/MM/AA">
                <div class="absolute top-0 bottom-0 right-0 flex justify-center place-items-center bg-[#F2F1FB] w-10 rounded-r cursor-pointer"
                    onclick="document.getElementById('date').showPicker()">
                    <img src="{{ asset('images/calendar icon.svg') }}" class="w-4 h-4" alt="calendar icon">
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="title" class="block text-[13px] text-[#9F9F9F] mb-1">Titolo</label>
            <input required type="text" id="title" wire:model="title"
                class="block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none focus:ring-0">
        </div>

        <div class="mb-3">
            <label for="userId" class="block text-[13px] text-[#9F9F9F] mb-1">Operatore</label>
            <select required id="userId" wire:model="userId"
                class="block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none focus:ring-0">
                <option value="">Seleziona operatore</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} {{ $user->surname }}</option>
                @endforeach
            </select>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 text-end">
            <button type="submit" class="bg-[#1E1B58] text-white px-4 py-2 rounded-md focus:outline-none">
                Conferma
            </button>
        </div>
    </form>
</div>
