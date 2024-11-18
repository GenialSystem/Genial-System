<div class="space-y-4">
    <div class="flex justify-between my-2">
        <span class="text-[#222222] text-[15px]">Silenzia tutte</span>
        <label for="silence_all" class="flex items-center cursor-pointer select-none">
            <div class="relative">
                <input type="checkbox" id="silence_all" wire:model.live="silence_all" class="peer sr-only" />
                <div
                    class="block w-10 h-[18px] rounded-full transition-colors
                           bg-[#F2F1FB] peer-checked:bg-[#1E1B58]">
                </div>
                <div
                    class="absolute left-1 top-1/2 -translate-y-1/2 w-3 h-3 rounded-full transition-transform bg-white
                           peer-checked:translate-x-[22px]">
                </div>
            </div>
        </label>
    </div>

    <div class="border-dashed border mb-2 mt-2"></div>

    <div class="flex justify-between my-2">
        <span class="text-[#222222] text-[15px]">Notifiche di nuove riparazioni assegnate</span>
        <label for="new_order_assigned" class="flex items-center cursor-pointer select-none">
            <div class="relative">
                <input type="checkbox" id="new_order_assigned" {{ $new_order_assigned ? 'checked' : '' }}
                    wire:model.live="new_order_assigned" class="peer sr-only" />
                <div
                    class="block w-10 h-[18px] rounded-full transition-colors
                           bg-[#F2F1FB] peer-checked:bg-[#1E1B58]">
                </div>
                <div
                    class="absolute left-1 top-1/2 -translate-y-1/2 w-3 h-3 rounded-full transition-transform bg-white
                           peer-checked:translate-x-[22px]">
                </div>
            </div>
        </label>
    </div>

    <div class="flex justify-between my-2">
        <span class="text-[#222222] text-[15px]">Notifiche di modifiche apportate alle riparazioni</span>
        <label for="order_state_change" class="flex items-center cursor-pointer select-none">
            <div class="relative">
                <input type="checkbox" id="order_state_change" {{ $order_state_change ? 'checked' : '' }}
                    wire:model.live="order_state_change" class="peer sr-only" />
                <div
                    class="block w-10 h-[18px] rounded-full transition-colors
                           bg-[#F2F1FB] peer-checked:bg-[#1E1B58]">
                </div>
                <div
                    class="absolute left-1 top-1/2 -translate-y-1/2 w-3 h-3 rounded-full transition-transform bg-white
                           peer-checked:translate-x-[22px]">
                </div>
            </div>
        </label>
    </div>
    @hasanyrole(['admin', 'mechanic'])
        <div class="flex justify-between my-2">
            <span class="text-[#222222] text-[15px]">Notifiche di eventi aggiunti al calendario</span>
            <label for="new_appointment" class="flex items-center cursor-pointer select-none">
                <div class="relative">
                    <input type="checkbox" id="new_appointment" {{ $new_appointment ? 'checked' : '' }}
                        wire:model.live="new_appointment" class="peer sr-only" />
                    <div
                        class="block w-10 h-[18px] rounded-full transition-colors
                           bg-[#F2F1FB] peer-checked:bg-[#1E1B58]">
                    </div>
                    <div
                        class="absolute left-1 top-1/2 -translate-y-1/2 w-3 h-3 rounded-full transition-transform bg-white
                           peer-checked:translate-x-[22px]">
                    </div>
                </div>
            </label>
        </div>
    @endhasanyrole
</div>
