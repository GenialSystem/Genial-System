{{-- <div class="mx-auto p-4 bg-white w-[1000px] rounded-md shadow-sm">
    @if ($errors->any())
        <div id="error-banner" class="bg-red-500 text-white text-center py-2 rounded-md my-4">
            {{ $errors->first() }}
        </div>
    @endif

    <h4 class="text-[#222222] font-semibold text-xl">{{ $customerId ? 'Modifica cliente' : 'Crea cliente' }}</h4>
    <div class="flex space-x-4 mb-4">
        <div id="step-indicator-1"
            class="text-sm font-medium {{ $step >= 1 ? 'bg-[#1E1B58] text-white' : 'bg-[#E0E0E0] text-[#9F9F9F]' }} rounded-full w-8 h-8 flex items-center justify-center">
            1
        </div>
        <div id="step-indicator-2"
            class="text-sm font-medium {{ $step >= 2 ? 'bg-[#1E1B58] text-white' : 'bg-[#E0E0E0] text-[#9F9F9F]' }} rounded-full w-8 h-8 flex items-center justify-center">
            2
        </div>
    </div>

    <form wire:submit.prevent="save" class="mt-4 text-[#9F9F9F] text-[13px]">
        <!-- Step 1 -->
        <div @if ($step != 1) class="hidden" @endif>
            <!-- Row 1 -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="name" class="block text-sm font-medium">Cliente</label>
                    <input type="text" wire:model="name" id="name"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('name') border-red-500 @enderror">
                    @error('name')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="assigned_cars" class="block text-sm font-medium">Auto assegnate</label>
                    <input type="number" wire:model="assigned_cars" id="assigned_cars"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('assigned_cars') border-red-500 @enderror">
                    @error('assigned_cars')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="city" class="block text-sm font-medium">Città</label>
                    <input type="text" wire:model="city" id="city"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('city') border-red-500 @enderror">
                    @error('city')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row 2 -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="admin_name" class="block text-sm font-medium">Responsabile</label>
                    <input type="text" wire:model="admin_name" id="admin_name"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('admin_name') border-red-500 @enderror">
                    @error('admin_name')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="cellphone" class="block text-sm font-medium">Cellulare</label>
                    <input type="text" wire:model="cellphone" id="cellphone"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('cellphone') border-red-500 @enderror">
                    @error('cellphone')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" wire:model="email" id="email"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('email') border-red-500 @enderror">
                    @error('email')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="border-dashed border mb-2 mt-4"></div>

            <!-- Row 3 -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="ragione_sociale" class="block text-sm font-medium">Ragione Sociale</label>
                    <input type="text" wire:model="ragione_sociale" id="ragione_sociale"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('ragione_sociale') border-red-500 @enderror">
                    @error('ragione_sociale')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="iva" class="block text-sm font-medium">Partita IVA</label>
                    <input type="text" wire:model="iva" id="iva"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('iva') border-red-500 @enderror">
                    @error('iva')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="pec" class="block text-sm font-medium">PEC</label>
                    <input type="text" wire:model="pec" id="pec"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('pec') border-red-500 @enderror">
                    @error('pec')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row 4 -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="sdi" class="block text-sm font-medium">SDI</label>
                    <input type="text" wire:model="sdi" id="sdi"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('sdi') border-red-500 @enderror">
                    @error('sdi')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="legal_address" class="block text-sm font-medium">Indirizzo Sede Legale</label>
                    <input type="text" wire:model="legal_address" id="legal_address"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('legal_address') border-red-500 @enderror">
                    @error('legal_address')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="cap" class="block text-sm font-medium">Cap</label>
                    <input type="text" wire:model="cap" id="cap"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('cap') border-red-500 @enderror">
                    @error('cap')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Row 5 -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="province" class="block text-sm font-medium">Provincia</label>
                    <input type="text" wire:model="province" id="province"
                        class="mt-1 block w-full px-3 py-2 border rounded-md focus:outline-none @error('province') border-red-500 @enderror">
                    @error('province')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Pulsanti di navigazione -->
            <div class="text-end h-8 mt-10">
                <button type="button" wire:click="nextStep"
                    class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm">Conferma</button>
            </div>
        </div>

        <!-- Step 2 -->
        <div @if ($step != 2) class="hidden" @endif>
            <span class="text-[15px] text-[#222222]">Seleziona quello che il cliente vedrà della sezione ‘dati
                generali’:</span>
            <div class="mb-4 text-[#9F9F9F] text-[13px]">
                <!-- Checkbox groups come nel tuo form originale -->
                <div class="mb-6 mt-6">
                    <input type="checkbox" wire:model="view_order_id"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_order_id" id="view_order_id">
                    <label for="view_order_id" class="ml-2 text-[#222222]">N. Riparazione</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_start_date"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_start_date" id="view_start_date">
                    <label for="view_start_date" class="ml-2 text-[#222222]">Data inizio</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_work_deadline"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_work_deadline" id="view_work_deadline">
                    <label for="view_work_deadline" class="ml-2 text-[#222222]">Scadenza lavori</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_branch"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_branch" id="view_branch">
                    <label for="view_branch" class="ml-2 text-[#222222]">Filiale</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_mechanic"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_mechanic" id="view_mechanic">
                    <label for="view_mechanic" class="ml-2 text-[#222222]">Tecnico</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_mechanic_earn"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_mechanic_earn" id="view_mechanic_earn">
                    <label for="view_mechanic_earn" class="ml-2 text-[#222222]">Guadagno Tecnico</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_car_data"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_car_data" id="view_car_data">
                    <label for="view_car_data" class="ml-2 text-[#222222]">Dati auto</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_total_price"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_total_price" id="view_total_price">
                    <label for="view_total_price" class="ml-2 text-[#222222]">Importo totale</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_iva"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_iva" id="view_iva">
                    <label for="view_iva" class="ml-2 text-[#222222]">IVA</label>
                </div>
                <span class="text-[15px] text-[#222222]">Seleziona quello che il cliente vedrà della sezione ‘scheda
                    tecnica’:</span>
                <div class="mb-6 mt-6">
                    <input type="checkbox" wire:model="view_technical_data"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_technical_data" id="view_technical_data">
                    <label for="view_technical_data" class="ml-2 text-[#222222]">Dati scheda tecnica</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_total_stamps"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_total_stamps" id="view_total_stamps">
                    <label for="view_total_stamps" class="ml-2 text-[#222222]">Totale bolli</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_assembly"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_assembly" id="view_assembly">
                    <label for="view_assembly" class="ml-2 text-[#222222]">Montaggio/Smontaggio</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_aluminum"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_aluminum" id="view_aluminum">
                    <label for="view_aluminum" class="ml-2 text-[#222222]">Alluminio</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_stamps_diameter"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_stamps_diameter" id="view_stamps_diameter">
                    <label for="view_stamps_diameter" class="ml-2 text-[#222222]">Diametro bolli</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_car_size"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_car_size" id="view_car_size">
                    <label for="view_car_size" class="ml-2 text-[#222222]">Dimensione veicolo</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_spare_parts"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_spare_parts" id="view_spare_parts">
                    <label for="view_spare_parts" class="ml-2 text-[#222222]">Ricambi</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_notes"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_notes" id="view_notes">
                    <label for="view_notes" class="ml-2 text-[#222222]">Note</label>
                </div>
                <span class="text-[15px] text-[#222222]">Seleziona quello che il cliente vedrà della sezione
                    ‘foto’:</span>
                <div class="mb-6 mt-6">
                    <input type="checkbox" wire:model="view_photos"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_photos" id="view_photos">
                    <label for="view_photos" class="ml-2 text-[#222222]">Foto</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_deassembly_photos"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_deassembly_photos" id="view_deassembly_photos">
                    <label for="view_deassembly_photos" class="ml-2 text-[#222222]">Foto smontaggio</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_photo_edits"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                        name="view_photo_edits" id="view_photo_edits">
                    <label for="view_photo_edits" class="ml-2 text-[#222222]">Modifica della foto</label>
                </div>
                <div class="mb-6">
                    <input type="checkbox" wire:model="view_upload_photos"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0"
                        name="view_upload_photos" id="view_upload_photos">
                    <label for="view_upload_photos" class="ml-2 text-[#222222]">Carica nuove foto</label>
                </div>
            </div>

            <!-- Pulsanti di navigazione -->
            <div class="flex justify-between mt-10">
                <button type="button" wire:click="previousStep"
                    class="py-2 px-4 bg-gray-500 text-white rounded-md text-sm">Indietro</button>
                <button type="submit" class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm">Salva</button>
            </div>
        </div>
    </form>
</div> --}}
