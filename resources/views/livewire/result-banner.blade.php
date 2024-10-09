<div id="result-banner"
    class="z-50 text-center py-2 fixed right-6 p-4 rounded-md w-80 shadow-md {{ $visible ? '' : 'hidden' }} 
           {{ $type === 'success' ? 'bg-[#E7F2DD] text-[#7FBC4B]' : 'bg-[#FDEDEC] text-[#E74C3C]' }}"
    x-data="{ show: true }" x-show="show" x-init="$dispatch('banner-hide')">

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
                const banner = document.querySelector('#result-banner');
                if (banner) {
                    banner.classList.add('hidden');
                }
            }, 4000);
        })
        // document.addEventListener('banner-hide', function() {
        //     setTimeout(() => {
        //         const banner = document.querySelector('#result-banner');
        //         if (banner) {
        //             banner.style.display = 'none';
        //         }
        //     }, 4000);
        // });
    });
</script>
