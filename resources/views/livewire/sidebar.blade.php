<aside id="default-sidebar"
    class="fixed top-0 left-0 h-screen transition-transform bg-[#E8E8E8] {{ $isCollapsed ? 'w-20' : 'w-64' }}"
    aria-label="Sidebar">


    <img id="logoLink" src="{{ asset('images/logo.svg') }}" alt="logo"
        class="w-28 object-cover my-8 mx-auto cursor-pointer px-1">

    <button id="toggleButton" type="button"
        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 end-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white {{ $isCollapsed ? 'hidden' : '' }}">
        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
        </svg>
        <span class="sr-only">X</span>
    </button>

    <div class="h-full px-3 py-4 overflow-y-auto flex flex-col {{ $isCollapsed ? 'hidden' : '' }}">
        <ul class="space-y-0.5 flex-grow">

            <li class="menu-item hover:bg-[#E0E0E0]">
                <a href="{{ route('home') }}"
                    class="flex items-center p-4 text-secondary group hover:text-primary rounded-lg">
                    <img src="{{ asset('images/casa icon.svg') }}" alt="">
                    <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Home</span>
                </a>
            </li>
            {{-- <li class="menu-item hover:bg-[#E0E0E0]">
                <a href="{{ route('customers.index') }}"
                    class="flex items-center p-4 rounded-lg group {{ request()->routeIs('customers.index') ? 'text-primary' : 'text-secondary hover:text-primary' }}">
                    <svg class="{{ request()->routeIs('customers.index') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' }}"
                        id="cliente_icona" data-name="cliente icona" xmlns="http://www.w3.org/2000/svg" width="10.215"
                        height="14.399" viewBox="0 0 10.215 14.399" fill="currentColor">
                        <g id="_212-User" data-name="212-User">
                            <path id="Tracciato_866" data-name="Tracciato 866"
                                d="M3.9,11.69a4.5,4.5,0,0,1,7.584-3.27l.616-.652a5.4,5.4,0,0,0-1.8-1.125,3.6,3.6,0,1,0-3.805,0A5.4,5.4,0,0,0,3,11.69v2.249a.45.45,0,0,0,.45.45h4.5v-.9H3.9Zm1.8-8.1a2.7,2.7,0,1,1,2.7,2.7,2.7,2.7,0,0,1-2.7-2.7Z"
                                transform="translate(-3 0.01)" />
                            <path id="Tracciato_867" data-name="Tracciato 867"
                                d="M18.717,24.073l-1.592-1.592a2.249,2.249,0,1,0-.634.634l1.592,1.592Zm-3.468-1.48A1.349,1.349,0,1,1,16.6,21.244,1.349,1.349,0,0,1,15.249,22.593Z"
                                transform="translate(-8.502 -10.444)" />
                        </g>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Clienti</span>
                </a>
            </li>

            <li class="menu-item hover:bg-[#E0E0E0]">
                <a href="{{ route('mechanics.index') }}"
                    class="flex items-center p-4 text-secondary group hover:text-primary {{ request()->routeIs('mechanics.index') ? 'text-primary' : 'text-secondary hover:text-primary' }} rounded-lgs rounded-lg">
                    <svg class="{{ request()->routeIs('mechanics.index') ? 'text-primary' : 'text-gray-500 group-hover:text-primary' }}"
                        xmlns="http://www.w3.org/2000/svg" width="12.2" height="13.915" viewBox="0 0 12.2 13.915"
                        fill="currentColor">
                        <g id="tecnici_icon" data-name="tecnici icon" transform="translate(0.1 0.1)">
                            <path id="Tracciato_103" data-name="Tracciato 103"
                                d="M656.874,668.137a1.042,1.042,0,0,1,.482.4,1.137,1.137,0,0,1,.175.621,1.108,1.108,0,0,1-.237.7,1,1,0,0,1-.43.32,11.184,11.184,0,0,1-.491,1.324,3.741,3.741,0,0,1-.956,1.324,1.387,1.387,0,0,1-.561.265,2.429,2.429,0,0,1-.587.055,2.547,2.547,0,0,1-.6-.055,1.327,1.327,0,0,1-.57-.274,3.881,3.881,0,0,1-.947-1.315,11.727,11.727,0,0,1-.491-1.315,1,1,0,0,1-.429-.32,1.145,1.145,0,0,1-.237-.7,1.2,1.2,0,0,1,.123-.529,1.07,1.07,0,0,1,.342-.4.194.194,0,0,1,.044-.027h0a3.578,3.578,0,0,0,.3-.21l.333.466a3.874,3.874,0,0,1-.342.237h0l-.035.027a.466.466,0,0,0-.149.173.616.616,0,0,0-.061.265.564.564,0,0,0,.114.347.354.354,0,0,0,.237.146l.184.018.053.183a10.526,10.526,0,0,0,.508,1.4,3.157,3.157,0,0,0,.789,1.114.85.85,0,0,0,.342.164,1.922,1.922,0,0,0,.473.037,2.349,2.349,0,0,0,.465-.037.749.749,0,0,0,.333-.155,3.052,3.052,0,0,0,.789-1.114,9.165,9.165,0,0,0,.508-1.406l.053-.183.184-.018a.353.353,0,0,0,.237-.146.563.563,0,0,0,.114-.347.547.547,0,0,0-.079-.3.374.374,0,0,0-.193-.164l.184-.548Z"
                                transform="translate(-648.27 -664.361)" fill="currentColor" stroke-width="0.2" />
                            <path id="Tracciato_104" data-name="Tracciato 104"
                                d="M359.438,1244.027a6.158,6.158,0,0,1,3.259,1.57,4.05,4.05,0,0,1,1.3,2.9v.493H352v-.493a4.05,4.05,0,0,1,1.3-2.9,6.028,6.028,0,0,1,3.259-1.57l.17-.027.107.146a1.09,1.09,0,0,0,.187.219,1.342,1.342,0,0,0,.839.374,1.49,1.49,0,0,0,1-.329,3.118,3.118,0,0,0,.321-.292l.107-.119.152.027Zm2.866,2a5.479,5.479,0,0,0-2.812-1.4,2.445,2.445,0,0,1-.295.256,2.055,2.055,0,0,1-1.375.438,1.927,1.927,0,0,1-1.187-.53,1.153,1.153,0,0,1-.143-.155,5.457,5.457,0,0,0-2.795,1.4,3.482,3.482,0,0,0-1.125,2.392h10.857a3.482,3.482,0,0,0-1.125-2.392Z"
                                transform="translate(-352 -1235.279)" fill="currentColor" stroke-width="0.2" />
                            <path id="Tracciato_105" data-name="Tracciato 105"
                                d="M620.933,320.365h0v-.009l-.009-.356.334.064a2.913,2.913,0,0,1,1.61.858,4.555,4.555,0,0,1,.924,1.433c.035.073.062.082.079.091.176.046.29.073.29.338v.137l-.106.091-1.372,1.169-.176.064H618.5l-.194-.082-1.214-1.169L617,322.9v-.128c0-.338.141-.329.352-.31,0,0-.009,0,0-.018a4.485,4.485,0,0,1,.906-1.5,2.932,2.932,0,0,1,1.645-.886l.334-.064-.009.356v.009h-.281l.044.274a2.366,2.366,0,0,0-1.337.721,3.864,3.864,0,0,0-.783,1.3.629.629,0,0,1-.1.192l.827.8h3.809l1-.849a.821.821,0,0,1-.123-.219,3.954,3.954,0,0,0-.8-1.251,2.368,2.368,0,0,0-1.32-.694l.044-.274Z"
                                transform="translate(-614.58 -319.406)" fill="currentColor" stroke-width="0.2" />
                            <path id="Tracciato_106" data-name="Tracciato 106"
                                d="M920,257.766v-1.808a.966.966,0,0,1,.959-.959h0a.966.966,0,0,1,.959.959v1.808h-.584v-1.808a.375.375,0,0,0-.365-.365h0a.374.374,0,0,0-.365.365v1.808H920Z"
                                transform="translate(-914.949 -255)" fill="currentColor" stroke-width="0.2" />
                        </g>
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Tecnici</span>
                </a>
            </li> --}}
            @role('admin')
                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="{{ route('customers.index') }}"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lg">
                        <img src="{{ asset('images/cliente icona.svg') }}" alt="" class="hover:fill-primary">
                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Clienti</span>
                    </a>
                </li>

                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="{{ route('mechanics.index') }}"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/tecnici icon.svg') }}" alt="">

                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Tecnici</span>
                    </a>
                </li>

                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="{{ route('orders.index') }}"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/commesse icona.svg') }}" alt="">

                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Riparazioni</span>
                    </a>
                </li>
            @endrole
            @role('customer')
                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="###################"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/commesse icona.svg') }}" alt="">

                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Riepilogo Riparazioni</span>
                    </a>
                </li>
            @endrole
            @hasanyrole(['admin', 'customer'])
                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="{{ route('estimates.index') }}"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/preventivi icona.svg') }}" alt="">

                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Preventivi</span>
                    </a>
                </li>
            @endhasanyrole
            @role('admin')
                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="/invoices_customers"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/fatture.svg') }}" alt="">
                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Fatture clienti</span>
                    </a>
                </li>
                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="/invoices_mechanics"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/fatture.svg') }}" alt="">
                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Fatture tecnici</span>
                    </a>
                </li>


                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="{{ route('done_orders') }}"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/car icon.svg') }}" alt="">

                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Riepilogo auto riparate</span>
                    </a>
                </li>
                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="{{ route('workstations.index') }}"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/filiali icon.svg') }}" alt="">

                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Postazioni di lavoro</span>
                    </a>
                </li>
                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="{{ route('calendar.index') }}"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/calendario menu.svg') }}" alt="">

                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Programmazione</span>
                    </a>
                </li>
                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="{{ route('office.index') }}"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/office.svg') }}" alt="">

                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Office</span>
                    </a>
                </li>
            @endrole
            @role('mechanic')
                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="###"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/calendario menu.svg') }}" alt="">

                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Il mio calendario</span>
                    </a>
                </li>
            @endrole
        </ul>

        <div class="relative flex place-content-center">
            <!-- Button -->
            <button id="dropdownButton"
                class="hover:shadow-sm bg-gradient-to-br from-[#FACCD1] to-[#D2D2FC] w-12 h-12 rounded-full flex items-center justify-center text-3xl text-white border-white border-2">
                +
            </button>

            <!-- Dropdown Menu -->
            <div id="dropdownMenu"
                class="absolute top-[-20px] left-1/2 transform -translate-x-1/2 -translate-y-full mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible transition-opacity duration-200">
                <ul class="text-[#222222] text-sm">
                    <li><a href="{{ route('customers.create') }}"
                            class="block px-4 py-3 hover:text-[#4453A5] border-b border-b-[#F0F0F0] cursor-pointer">Crea
                            Nuovo
                            Cliente
                        </a></li>
                    <li wire:click="$dispatch('openModal', { component: 'mechanic-form'})"
                        class="block px-4 py-3 hover:text-[#4453A5] border-b border-b-[#F0F0F0] cursor-pointer">Crea
                        Nuovo
                        Tecnico
                    </li>
                    <li><a href="{{ route('orders.create') }}"
                            class="block px-4 py-3 hover:text-[#4453A5] border-b border-b-[#F0F0F0] cursor-pointer">Crea
                            Nuova
                            Riparazione
                        </a></li>
                    <li wire:click="$dispatch('openModal', { component: 'estimate-modal'})"
                        class="block px-4 py-3 hover:text-[#4453A5] border-b border-b-[#F0F0F0] cursor-pointer">Crea
                        Nuovo
                        Preventivo
                    </li>
                </ul>
            </div>
        </div>


    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownMenu = document.getElementById('dropdownMenu');
        const toggleButton = document.getElementById('toggleButton');
        const sidebar = document.getElementById('default-sidebar');
        const logoLink = document.getElementById('logoLink');
        const menuDiv = sidebar.querySelector('div.h-full'); // Get the menu div

        // Show/hide dropdown
        dropdownButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('opacity-0');
            dropdownMenu.classList.toggle('invisible');
        });

        // Sidebar toggle
        toggleButton.addEventListener('click', () => {
            toggleSidebar();
        });

        // Logo click to toggle sidebar when collapsed
        logoLink.addEventListener('click', () => {
            if (sidebar.classList.contains('w-20')) {
                toggleSidebar();
            }
        });

        function toggleSidebar() {
            sidebar.classList.toggle('w-20');
            sidebar.classList.toggle('w-64');

            // Hide/show elements based on the sidebar state
            if (sidebar.classList.contains('w-20')) {
                toggleButton.classList.add('hidden'); // Hide toggle button
                menuDiv.classList.add('hidden'); // Hide menu div
            } else {
                toggleButton.classList.remove('hidden'); // Show toggle button
                menuDiv.classList.remove('hidden'); // Show menu div
            }

            // Emit event to Livewire to toggle the state
            Livewire.dispatch('toggleSidebar', {
                collapsed: sidebar.classList.contains('w-20')
            });
        }
    });
</script>
