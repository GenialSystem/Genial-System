<div id="result-banner"
    class="z-50 text-center py-2 fixed right-6 p-4 rounded-md w-80 shadow-md 
           transition-all duration-100 transform {{ $visible ? 'opacity-100 translate-y-0' : 'opacity-0 -translate-y-4 pointer-events-none' }}
           {{ $type === 'success' ? 'bg-[#E7F2DD] text-[#7FBC4B]' : ($type === 'error' ? 'bg-[#FDEDEC] text-[#E74C3C]' : 'bg-[#FFF4CC] text-[#E8A723]') }}"
    x-data="{ show: @entangle('visible') }" x-show="show" x-transition:enter="transition ease-out duration-100"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform -translate-y-4">

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
                Livewire.dispatch('hideBanner');
            }, 3000); // Set to 2 seconds (2000 milliseconds)
        });
    });
</script>
