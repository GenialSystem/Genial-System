<div class=" bg-[#707070] bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-md w-[1000px]">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-[#222222] mb-0">
                {{ $invoice->id ? 'Modifica preventivo' : 'Nuovo preventivo' }}
            </h3>
            <button wire:click="$dispatch('closeModal')" class="text-gray-500 hover:text-[#9F9F9F] text-3xl">
                &times;
            </button>
        </div>

        <form wire:submit.prevent="updateinvoice">
            <div class="grid grid-cols-2 gap-4">

                <div class="mb-4 relative">
                    <label
                        class="block text-sm font-medium text-[#9F9F9F] text-[13px]">{{ $role === 'customer' ? Cliente : 'Tecnico' }}</label>

                    <div class="relative">
                        <!-- Customer Select Dropdown -->
                        <select required wire:model.live="selectedUser"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none appearance-none">
                            <option value="">Seleziona un cliente</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>

                        @if ($users->find($selectedUser))
                            <span
                                class="absolute inset-y-0 left-0 h-[70%] flex items-center bg-[#F2F1FB] text-[#4453A5] font-semibold rounded-md ml-2 px-4 pointer-events-none truncate"
                                style=" top: 50%; transform: translateY(-50%);">
                                <!-- Adjusted width to fit within the select -->
                                {{ $users->find($selectedUser)->name ?? 'N/A' }}

                            </span>
                        @endif
                    </div>
                </div>
                @if ($role === 'customer')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Responsabile</label>
                        <div class="relative">
                            <input required type="text"
                                value="{{ $users->find($selectedUser)->customerInfo->admin_name ?? null }}"
                                class="text-transparent mt-1 block w-full pl-12 px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                            <span
                                class="absolute h-[70%] inset-y-0 left-0 flex items-center bg-[#F2F1FB] text-[#4453A5] font-semibold rounded-md ml-2 px-4 pointer-events-none truncate"
                                style=" top: 50%; transform: translateY(-50%);">
                                {{ $users->find($selectedUser)->customerInfo->admin_name ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                @endif

            </div>

            <div class="border-dashed border border-[#E8E8E8] my-4"></div>

            <div class="grid grid-cols-2 gap-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">N° Fattura</label>
                    <input wire:model='number' type="text" value="{{ $invoice->number ?? $invoiceNumber }}"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Data</label>
                    <input required type="text"
                        value="{{ $invoice ? \Carbon\Carbon::parse($invoice['created_at'])->format('d/m/Y') : '' }}"
                        class="mt-1 block w-full pointer-events-none px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                </div>

            </div>

            <div class="grid grid-cols-2 gap-4">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Imponibile</label>
                    <input wire:model='price' required type="number" name="price" step="0.01" min="1"
                        value="{{ $invoice ? $invoice->price : '' }}"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">

                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-[#9F9F9F] text-[13px]">IVA</label>
                    <input wire:model='iva' required type="text" name="iva"
                        value="{{ $invoice ? $invoice->iva : '' }}"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">

                </div>
            </div>
            <div class="text-end h-8 mt-10">
                <button type="submit" class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm" wire:loading.remove>
                    Conferma
                </button>
                <button disabled class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm hidden" wire:loading>
                    <div class="flex items-center">

                        <div
                            class="animate-spin rounded-full h-5 w-5 border-t-2 border-b-2 border-white] border-t-transparent mr-2">
                        </div>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>
