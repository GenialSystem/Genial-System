<div class="">
    <h2 class="text-2xl font-semibold">Office</h2>
    <div class="bg-white p-4 mt-4 h-screen">
        <div class="mb-6 flex justify-between h-8">
            <input type="text" class="p-2 border border-gray-300 rounded xl:w-[600px]" placeholder="Cerca elemento..."
                wire:model.debounce.300ms.live="searchTerm" />

            <div class="relative z-30" x-data="{ open: false }">
                <button @click="open = !open" class="ml-4 py-1 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-full">
                    Aggiungi nuovo </button>

                <!-- Dropdown menu -->
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 bg-white border border-gray-300 rounded shadow-lg">
                    <ul class="text-[#222222] text-sm">
                        <li @click="open = !open"
                            wire:click="$dispatch('openModal', { component: 'office-upload-modal', arguments:{type:'file', parentFolderId: '{{ $folderId }}'}})"
                            class="block cursor-pointer px-4 py-3 hover:text-[#4453A5]">Carica file
                        </li>
                        <li @click="open = !open"
                            wire:click="$dispatch('openModal', { component: 'office-upload-modal', arguments:{type:'folder', parentFolderId: '{{ $folderId }}'}})"
                            class="block cursor-pointer px-4 py-3 hover:text-[#4453A5]">
                            Crea cartella
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        @if ($folderId)
            <div class="mb-4">
                <button wire:click='goBack' class="text-[#808080] text-[13px] my-2 flex place-items-center"><img
                        src="{{ asset('images/Icon ionic-ios-arrow-back.svg') }}" alt="back-icon"
                        class="mr-1.5">Indietro</button>
            </div>
        @endif

        <div class="grid grid-cols-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-6 gap-4 mb-6">
            @forelse ($items as $item)
                <div>
                    <div class="w-full h-52 mb-6">
                        <div
                            class="bg-[#F5F5F5] h-3/4 flex flex-col justify-center place-items-center text-3xl relative">
                            <div class="absolute top-0 right-2" x-data="{ open: false }">
                                <div @click="open = !open"
                                    class="bg-white text-lg cursor-pointer flex justify-center place-items-center mt-2">
                                    <span class="pb-1">&#8230;</span>
                                </div>

                                <!-- Dropdown menu -->
                                <div x-show="open" @click.away="open = false"
                                    class="absolute left-0 mt-2 z-40 bg-white border border-gray-300 rounded shadow-lg w-max">
                                    <ul class="text-[#222222] text-xs">
                                        <li @click="open = !open" wire:click="downloadItem({{ $item->id }})"
                                            class="block cursor-pointer px-2 py-2 hover:text-[#4453A5]">Scarica
                                            @if ($item->type == 'folder')
                                                cartella
                                            @else
                                                file
                                            @endif
                                        </li>
                                        <li @click="open = !open"
                                            wire:click="$dispatch('openModal', { component: 'office-rename-item', arguments: { type: '{{ $item->type }}', itemId: '{{ $item->id }}' }})"
                                            class="block cursor-pointer px-2 py-2 hover:text-[#4453A5]">
                                            Rinomina @if ($item->type == 'folder')
                                                cartella
                                            @else
                                                file
                                            @endif
                                        </li>
                                        <li @click="open = !open" wire:click="deleteItem({{ $item->id }})"
                                            class="block cursor-pointer px-2 py-2 hover:text-[#4453A5]">
                                            Elimina @if ($item->type == 'folder')
                                                cartella
                                            @else
                                                file
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div>
                                @if ($item->type == 'folder')
                                    📁
                                @else
                                    📄
                                @endif
                            </div>
                        </div>

                        <div class="bg-white border-x border-b p-3">
                            <div class="text-[#27272A] text-sm truncate">
                                <span
                                    @if ($item->type == 'folder') wire:click="enterFolder({{ $item->id }})" @endif
                                    class="cursor-pointer">
                                    {{ $item->name }}
                                </span>
                            </div>
                            <span class="text-[#747474] text-xs">
                                {{ $item->created_at->locale('it')->translatedFormat('d M Y') }}

                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="absolute text-xl">Nessun file presente</div>
            @endforelse
        </div>
    </div>
</div>
