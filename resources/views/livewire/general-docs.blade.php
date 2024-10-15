<div>
    <div>
        <div class="mb-4 flex justify-between h-8">
            <input type="text" class="p-2 border border-gray-300 rounded w-[600px]" placeholder="Cerca elemento..."
                wire:model.debounce.300ms.live="searchTerm" />
            <button
                wire:click="$dispatch('openModal', { component: 'general-docs-modal', arguments:{'modelId' : {{ $modelId }}, 'isOrderModel': {{ $isOrderModel }}} })"
                class="bg-[#F2F1FB] flex justify-center place-items-center px-2 duration-200 py-1 hover:bg-[#4453A5] text-[#4453A5] text-[13px] hover:text-white rounded-md group">
                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2" width="13.937" height="13.937"
                    viewBox="0 0 13.937 13.937">
                    <g id="Icon_feather-download" data-name="Icon feather-download" transform="translate(-3.75 -3.75)">
                        <path id="Tracciato_630" data-name="Tracciato 630"
                            d="M16.937,22.5v2.764a1.382,1.382,0,0,1-1.382,1.382H5.882A1.382,1.382,0,0,1,4.5,25.264V22.5"
                            transform="translate(0 -9.709)" fill="none" stroke="#4453A5" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5"
                            class="transition-colors duration-200 group-hover:stroke-white" />
                        <path id="Tracciato_631" data-name="Tracciato 631" d="M10.5,15l3.455,3.455L17.409,15"
                            transform="translate(-3.236 -5.663)" fill="none" stroke="#4453A5" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5"
                            class="transition-colors duration-200 group-hover:stroke-white" />
                        <path id="Tracciato_632" data-name="Tracciato 632" d="M18,12.791V4.5"
                            transform="translate(-7.282)" fill="none" stroke="#4453A5" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="1.5"
                            class="transition-colors duration-200 group-hover:stroke-white" />
                    </g>
                </svg>
                Carica documenti
            </button>
        </div>
        <div class="grid grid-cols-5 gap-4 mb-2">
            @forelse ($docs as $item)
                <div wire:key="doc-{{ $item->id }}">
                    <div class="w-full h-52 mb-6">
                        <a href="{{ Storage::url($item->file_path) }}" target="_blank">
                            <div class="bg-[#F2F1FB] h-3/4 flex flex-col justify-center place-items-center text-3xl">
                                <div>
                                    📄
                                </div>
                            </div>
                            <div class="bg-white border-x border-b p-3">
                                <div class="text-[#27272A] text-sm truncate">
                                    <span>{{ $item->name }}</span>
                                </div>
                                <span class="text-[#747474] text-xs">
                                    {{ $item->created_at->locale('it')->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-start text-xl">Nessun file presente</div>
            @endforelse
        </div>

    </div>
</div>
