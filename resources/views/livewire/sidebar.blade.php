<aside id="default-sidebar"
    class="fixed top-0 left-0 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 bg-[#E8E8E8]"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto flex flex-col">
        <ul class="space-y-0.5 flex-grow">

            <li class="menu-item hover:bg-[#E0E0E0]">
                <a href="{{ route('home') }}"
                    class="flex items-center p-4 text-secondary group hover:text-primary rounded-lg">
                    <img src="{{ asset('images/casa icon.svg') }}" alt="">
                    <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Home</span>
                </a>
            </li>
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
                    <a href="####"
                        class="flex items-center p-4 text-secondary group hover:text-primary rounded-lgs rounded-lg group">
                        <img src="{{ asset('images/calendario menu.svg') }}" alt="">

                        <span class="flex-1 ms-3 whitespace-nowrap text-[15px]">Programmazione</span>
                    </a>
                </li>
                <li class="menu-item hover:bg-[#E0E0E0]">
                    <a href="##"
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
                    <li><a href="#" class="block px-4 py-3 hover:text-[#4453A5] border-b border-b-[#F0F0F0]">Crea
                            Nuovo
                            Cliente
                        </a></li>
                    <li><a href="#" class="block px-4 py-3 hover:text-[#4453A5] border-b border-b-[#F0F0F0]">Crea
                            Nuovo
                            Tecnico
                        </a></li>
                    <li><a href="#" class="block px-4 py-3 hover:text-[#4453A5] border-b border-b-[#F0F0F0]">Crea
                            Nuova
                            Riparazione
                        </a></li>
                    <li><a href="#" class="block px-4 py-3 hover:text-[#4453A5] border-b border-b-[#F0F0F0]">Crea
                            Nuovo
                            Preventivo
                        </a></li>
                </ul>
            </div>
        </div>


    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Get all anchor elements inside .menu-item
        const menuItems = document.querySelectorAll('.menu-item a');

        // Function to set active class based on local storage
        function setActiveClass() {
            const activeItemId = localStorage.getItem('activeMenuItem');
            menuItems.forEach(item => {
                if (item.getAttribute('href') === activeItemId) {
                    item.classList.add('text-primary');
                    item.classList.remove('text-secondary');
                } else {
                    item.classList.remove('text-primary');
                    item.classList.add('text-secondary');
                }
            });
        }

        // Call setActiveClass on page load
        setActiveClass();

        // Loop through each item and add a click event listener
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                // Save the clicked item's href to local storage
                localStorage.setItem('activeMenuItem', item.getAttribute('href'));

                // Update the active class for all items
                setActiveClass();
            });
        });
        const dropdownButton = document.getElementById('dropdownButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (dropdownButton && dropdownMenu) {
            dropdownButton.addEventListener('click', () => {
                dropdownMenu.classList.toggle('opacity-0');
                dropdownMenu.classList.toggle('invisible');
            });
        }
    });
</script>
