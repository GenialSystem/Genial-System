<div class="fixed inset-0 bg-[#707070] bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-md w-[1000px]">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-[#222222] mb-0">
                {{ $estimate ? 'Modifica preventivo' : 'Nuovo preventivo' }}
            </h3>
            <button wire:click="closeModal" class="text-gray-500 hover:text-[#9F9F9F] text-3xl">
                &times;
            </button>
        </div>
        <form wire:submit.prevent="updateEstimate">
            <div class="grid grid-cols-3 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Cliente</label>
                    <div class="relative">
                        <input type="text"
                            class="mt-1 block w-full pointer-events-none pl-12 px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none relative">
                        <span
                            class="flex place-items-center absolute inset-y-0 left-0 h-[70%] bg-[#F2F1FB] text-[#4453A5] font-semibold rounded-md ml-2 my-auto px-2 text-[13px]">
                            {{ $estimate ? $estimate['customer']['name'] : 'N/A' }} &times;</span>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Responsabile</label>
                    <div class="relative">
                        <input type="text"
                            class="mt-1 block w-full pointer-events-none pl-12 px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none relative">
                        <span
                            class="flex place-items-center absolute inset-y-0 left-0 h-[70%] bg-[#F2F1FB] text-[#4453A5] font-semibold rounded-md ml-2 my-auto px-2 text-[13px]">
                            {{ isset($estimate['customer']['customerInfo']['admin_name']) ? $estimate['customer']['customerInfo']['admin_name'] : 'N/A' }}
                            &times;</span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Email</label>
                    <input type="text"
                        value="{{ isset($estimate['customer']['email']) ? $estimate['customer']['email'] : '' }}"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                </div>
            </div>

            <div class="border-dashed border border-[#E8E8E8] my-4"></div>

            <div class="grid grid-cols-3 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">N° preventivo</label>
                    <input type="text" value="{{ $estimate ? $estimate['id'] : '' }}"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Data</label>
                    <input type="text"
                        value="{{ $estimate ? \Carbon\Carbon::parse($estimate['created_at'])->format('d/m/Y') : '' }}"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Tipologia di lavorazione</label>
                    <select wire:model="newType"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        @foreach ($types as $key => $value)
                            <option value="{{ $key }}" {{ $newType === $key ? 'selected' : '' }}>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Prezzo</label>
                    <input type="text" value="{{ $estimate ? $estimate->price : '' }}"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                </div>
            </div>

            <div class="text-end h-8 mt-10">
                <button type="submit" class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm">Conferma</button>
            </div>
        </form>
    </div>
</div>
