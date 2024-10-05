<div class="flex justify-between">
    <div class="overflow-y-auto max-h-[800px] flex-grow">
        @forelse ($archives as $activity)
            <div class="flex space-x-2 place-items-center relative z-10">
                <!-- Set a fixed width for the date span -->
                <span
                    class="text-[#9F9F9F] text-sm w-32">{{ \Carbon\Carbon::parse($activity->date)->translatedFormat('d F Y') }}</span>
                <div class="relative h-40 w-8 flex items-center justify-center">
                    <!-- Vertical Line -->
                    <div class="absolute h-full w-[1px] border border-dashed border-[#E8E8E8]"></div>
                    <!-- Dot -->
                    <div class="absolute w-2.5 h-2.5 rounded-full border-2 border-[#4453A5] bg-white"></div>
                </div>
                <div>
                    <span class="font-semibold text-[#222222] text-lg">{{ $activity->title }}</span>
                    <div class="flex space-x-1 mt-2">
                        <img src="{{ asset($mechanic->user->image_path ?? 'images/placeholder.png') }}"
                            alt="profile image" class="w-8 h-8 rounded-full">
                        <span>Operatore: </span>
                        <span> {{ $activity->user->name }} {{ $activity->user->surname }}</span>
                    </div>
                </div>
            </div>
        @empty
            <span>Nessuno storico attività per questo utente</span>
        @endforelse
    </div>
    <button
        wire:click="$dispatch('openModal', { component: 'activity-modal', arguments : {'customerId' : {{ $customerId }}}})"
        class="px-2 bg-[#1E1B58] text-white rounded-md text-sm h-8">+
        Aggiungi attività</button>
</div>
