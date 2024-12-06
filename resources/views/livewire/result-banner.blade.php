<div id="result-banner"
    class="z-50 text-center py-2 fixed right-6 p-4 rounded-md w-80 shadow-md
           transition-all duration-300 transform {{ $visible ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4 pointer-events-none' }}
           {{ $type === 'success' ? 'bg-[#E7F2DD] text-[#7FBC4B]' : ($type === 'error' ? 'bg-[#FDEDEC] text-[#E74C3C]' : 'bg-[#FFF4CC] text-[#E8A723]') }}">

    <div class="flex justify-between font-semibold mb-2 text-[15px]">
        <span>{{ $title }}</span>
        <button @click="show = false">X</button>
    </div>
    <p class="text-start text-[13px]">{{ $subtitle }}</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('banner-auto-hide', function() {
            setTimeout(() => {
                console.log('AUTO HID');
                // Manda il comando di nascondere il banner dopo la transizione
                Livewire.dispatch('hideBanner');
            }, 2200); // Nascondi il banner dopo 3 secondi
        });
    });
</script>
