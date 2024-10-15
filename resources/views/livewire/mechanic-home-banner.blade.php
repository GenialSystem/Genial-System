<div class="flex justify-between h-24">
    <div class="bg-white w-max flex place-items-center p-4 shadow-[0px_0px_11px_rgba(116,116,116,0.09)]">
        <div class="rounded-md bg-[#5E66CC] mr-4 p-4 w-16 h-16 flex place-items-center justify-center mx-auto">
            <img src="{{ asset('images/car.svg') }}" alt="" />
        </div>
        <span class="text-[#9F9F9F] text-sm mr-2">Seleziona anno:</span>

        <!-- Date Input -->
        <div id="dateInput"
            class="border-[#E0E0E0] border flex justify-between items-center h-8 w-32 rounded-md cursor-pointer"
            onclick="openModal()">
            <span id="dateDisplay" class="ml-2 text-[#9F9F9F] flex items-center">{{ $selectedYear }}</span>
            <div class="bg-[#E8E8E8] rounded-r w-8 h-full flex justify-center items-center">
                <img src="{{ asset('images/calendar icon.svg') }}" class="w-3" alt="calendar icon">
            </div>
        </div>

        <!-- Calendar Modal -->
        <div class="relative flex">

            <div id="calendarModal"
                class="hidden absolute top-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg w-48 z-50">
                <div class="p-4">
                    <select id="yearSelect" wire:model.live="selectedYear"
                        class="w-full bg-white border border-gray-300 rounded-md p-2" onchange="closeModal()">
                        @foreach (range(2020, \Carbon\Carbon::now()->year) as $year)
                            <option value="{{ $year }}">Anno {{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="border-l border-l-[#F0F0F0] ml-6 px-6 text-center">
            <div class="text-[#222222] text-[22px] font-semibold">{{ $orderCountPerYear }}</div>
            <span class="text-[15px] text-[#464646]">Totale auto riparate</span>
        </div>
    </div>
    <a href="{{ route('orders.create') }}">
        <button class="bg-[#282465] h-full rounded-md text-white font-semibold text-[15px] px-4">Nuova scheda
            auto</button></a>
</div>


<script>
    function openModal() {
        document.getElementById('calendarModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('calendarModal').classList.add('hidden');
    }

    // Optionally close the modal if clicking outside
    window.onclick = function(event) {
        if (!event.target.closest('#dateInput') && !event.target.closest('#calendarModal')) {
            closeModal();
        }
    }
</script>
