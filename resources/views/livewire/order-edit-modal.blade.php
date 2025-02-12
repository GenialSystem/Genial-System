<div class=" overflow-auto justify-center items-center">
    <div class="p-6 bg-white rounded-md shadow-sm relative">
        <!-- Close button -->

        <h2 class="text-lg font-semibold mb-4">Modifica commessa #{{ $order->id }}</h2>

        <!-- Form -->
        <form id="updateOrderForm" action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <span class="text-[#222222] text-[15px] mt-4">Inserire il numero di bolli presenti sulla vettura.</span>
            <div class="grid grid-cols-3 gap-4 my-6">
                @foreach ($parts as $index => $part)
                    <div>
                        <label for="part_{{ $index }}"
                            class="block text-sm text-[13px] text-[#9F9F9F]">{{ $part->name }}</label>
                        <input required type="number" min="0"
                            value="{{ $order->carParts->where('name', $part->name)->first()->pivot->damage_count ?? 0 }}"
                            name="parts[{{ $index }}][damage_count]" id="part_{{ $index }}"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <!-- Store only the name, not the entire object -->
                        <input type="hidden" name="parts[{{ $index }}][name]" value="{{ $part->name }}">
                        <span id="part_{{ $index }}-error" class="text-red-500 text-xs hidden"></span>
                    </div>
                @endforeach
                <div>
                    <label for="car_size" class="block text-sm text-[13px] text-[#9F9F9F]">Dimensioni veicolo</label>
                    <select name="car_size" id="car_size"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <option value="">- Seleziona -</option>
                        @foreach ($car_sizes as $car_size)
                            <option value="{{ $car_size }}" {{ $car_size == $order->car_size ? 'selected' : '' }}>
                                {{ $car_size }}
                            </option>
                        @endforeach
                    </select>
                    <span id="car_size-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
                <div>
                    <label for="damage_diameter" class="block text-sm text-[13px] text-[#9F9F9F]">Diametro bolli</label>
                    <input required type="text" value="{{ $order->damage_diameter ?? '' }}" name="damage_diameter"
                        id="damage_diameter"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">

                    <span id="damage_diameter-error" class="text-red-500 text-xs hidden"></span>
                </div>
                @role('admin')

                {{-- montaggio/smontaggio --}}
                 <div>
                  <label for="assembly_deassembly"
                                    class="block text-sm text-[#9F9F9F] text-[13px]">Montaggio/Smontaggio</label>
                                    <select name="assembly_disassembly" id="assembly_disassembly" class="mt-1 p-2 border-transparent rounded-md w-20 bg-white">
                                    <option value="1" <?= $order->assembly_disassembly ? 'selected' : '' ?>>Si</option>
                                    <option value="0" <?= !$order->assembly_disassembly ? 'selected' : '' ?>>No</option>
                                </select>
                    <span id="assembly_deassembly-error" class="text-red-500 text-xs hidden"></span>
                </div>
                {{-- --------------------- --}}
                    <div>
                        <label for="earn_mechanic_percentage" class="block text-sm text-[13px] text-[#9F9F9F]">Guadagno
                            Tecnico %</label>
                        <input required type="number" value="{{ $order->earn_mechanic_percentage ?? '' }}" min="0"
                            max="99" name="earn_mechanic_percentage" id="earn_mechanic_percentage"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="earn_mechanic_percentage-error" class="text-red-500 text-xs hidden">Campo
                            obbligatorio.</span>
                    </div>
                @endrole

                <div>
                    <label for="color" class="block text-sm text-[13px] text-[#9F9F9F]">Colore</label>
                    <input required type="text" value="{{ $order->color ?? '' }}" name="color" id="color"
                        class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                    <span id="color-error" class="text-red-500 text-xs hidden">Campo
                        obbligatorio.</span>
                </div>
                <div class="flex place-items-center">
                    <input {{ $order->aluminium ? 'checked' : '' }} type="checkbox" name="aluminium"
                        class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"><span
                        class="text-[15px] text-[#222222] ml-2">Alluminio</span>
                    <span id="aluminium-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                </div>
            </div>

            <label class="block text-sm text-[13px] text-[#9F9F9F]" for="replacements">Ricambi</label>
            <textarea required name="replacements" id="replacements" cols="20" rows="5"
                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">{{ $order->replacements }}</textarea>
            <span id="replacements-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>

            <!-- Notes Textarea value="" -->
            <label class="block text-sm text-[13px] text-[#9F9F9F] mt-4" for="notes">Appunti</label>
            <textarea required name="notes" id="notes" cols="10" rows="5"
                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">{{ $order->notes }}</textarea>
            <span id="notes-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>

            <div class="text-end h-8 mt-10">
                <button type="button" wire:click="$dispatch('closeModal')"
                    class="mr-4 py-2 px-4 bg-[#E8E8E8] text-[#222222] rounded-md text-sm">Annulla</button>
                <button type="submit" class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm">Conferma</button>
            </div>
        </form>
    </div>
</div>
