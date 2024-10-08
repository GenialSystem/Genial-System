<div x-data="{ visible: @entangle('visible') }" x-show="visible" x-init="$watch('visible', value => {
    if (value) {
        setTimeout(() => visible = false, 4000);
    }
})"
    class="z-50 text-center py-2 fixed right-6 p-4 rounded-md w-80 shadow-md
            {{ $type === 'success' ? 'bg-[#E7F2DD] text-[#7FBC4B]' : 'bg-[#FDEDEC] text-[#E74C3C]' }}"
    x-transition.duration.500ms>

    <div class="flex justify-between font-semibold mb-2 text-[15px]">
        <span>{{ $title }}</span>
        <button @click="visible = false">X</button>
    </div>
    <p class="text-start text-[13px]">{{ $subtitle }}</p>
</div>
