@extends('layout')

@section('content')
    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">
        Calendario</h4>

    <div class="bg-white pb-8">
        <div class="flex justify-between px-4 pt-4">
            <div class="flex">
                <button id="prev-month"
                    class="border border-[#E8E8E8] rounded-md h-7 text-center mr-2 p-2 flex justify-center place-items-center">&lt;</button>
                <button id="next-month"
                    class="border border-[#E8E8E8] rounded-md h-7 text-center mr-4 p-2 flex justify-center place-items-center">&gt;</button>
                <button id="today" class="bg-[#4453A5] text-white rounded-md h-7 text-[14px] px-2">Oggi</button>
            </div>
            <h5 id="current-month" class="text-lg font-semibold"></h5>
            <div class="flex">
                @role('admin')
                    <button id="calendar-btn"
                        class="text-[#4453A5] font-semibold border-b-2 border-[#4453A5] px-2">Calendario</button>
                    <button id="schedule-btn" class="text-black px-2 ml-4">Programmazione</button>
                @endrole
            </div>
        </div>

        {{-- Calendar Section --}}
        <div id="calendar-section" class="">
            <div class="border-dashed border my-5"></div>

            <div class="mb-4 xl:flex justify-between items-center px-4 space-y-3 xl:space-y-0">
                <div class="flex flex-col xl:flex-row space-y-3 xl:space-y-0">
                    {{-- <input type="text" class="border border-gray-300 rounded xl:mr-6 h-8 2xl:w-[600px]"
                        placeholder="Cerca elemento..." wire:model.debounce.300ms.live="searchTerm" /> --}}

                    @role('admin')
                        <select id="mechanic-filter"
                            class="pr-12 border border-gray-300 rounded text-gray-600 text-sm h-8 leading-none w-[225px]">
                            <option value="">Filtra per tecnico</option>
                            @foreach ($mechanics as $mechanic)
                                <option value="{{ $mechanic->id }}">{{ $mechanic->user->name }} {{ $mechanic->user->surname }}
                                </option>
                            @endforeach
                        </select>
                    @endrole
                </div>
                <div class="text-end">
                    <button onclick="Livewire.dispatch('openModal', { component: 'event-modal'})"
                        class="py-1 px-2 bg-[#1E1B58] text-white rounded-md text-sm h-8">+ Crea nuovo
                        evento</button>

                </div>
            </div>
            <div class="p-4" id="calendar"></div>
        </div>

        {{-- Schedule Section --}}

        <div id="schedule-section" class="my-8 mx-4 hidden">
            <x-top-scrollbar id="top-scrollbar" />

        {{-- Matteo Denni 6/02/2025 ho aggiunto l'altezza h-3/4 della tabella per non far scrollare l'header della tabella --}}
            <div id="table-container" class="overflow-y-auto overflow-x-auto h-3/4">
            {{-- Matteo Denni 6/02/2025 per adattare la tabella Programmazione allo schermo tolgo la classe table-fixed dall'elemento table --}}
            {{-- Matteo Denni 7/02/2025 table-fixed inserito di nuovo dopo scroll v e o --}}
                <table id="schedule-table" class="w-full bg-white border border-gray-200 rounded-md table-fixed">
                        {{-- Matteo Denni 6/02/2025 con sticky e top-0 uniti all'altezza del div padre l'header rimane fisso e z-20 evita di mostrare gli elementi che scorrono, left-n evita di sovrastare la prima colonna --}}
                    <thead class="bg-[#F5F5F5] text-start sticky top-0 z-20 shadow">
                        <tr>
                            <th
                                class="sticky left-0 border-r text-start px-2 text-[#4453A5] bg-[#F2F1FB] text-[15px] font-normal p-4 border border-dashed border-r-[#4453A5] w-28">
                                Data
                            </th>
                            <th class="sticky left-28 border-r text-center px-2 text-[#4453A5] bg-[#F2F1FB] text-[15px] font-normal w-28">
                                Giorno
                            </th>
                            @foreach ($mechanics as $mechanic)
                                <th class="text-start px-2 bg-[#F5F5F5] text-[15px] text-[#808080] font-normal w-64">
                                    {{ $mechanic->user->name . ' ' . $mechanic->user->surname }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-start">
                        {{-- Rows will be dynamically populated by JS --}}
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        let eventElementMap = new Map();
        const eventColorMap = new Map();
        const availabilities = @json($availabilities);

        function createCalendar() {
            calendar = new Calendar(calendarEl, {
                plugins: [DayGridPlugin],
                dayMaxEventRows: true,
                views: {
                    timeGrid: {
                        dayMaxEventRows: 6
                    }
                },
                dayHeaderFormat: {
                    weekday: 'long'
                },
                eventDidMount: function(info) {

                    eventElementMap.set(info.event.id, info.el);

                    // Define the color pairs
                    const colorPairs = [{
                            backgroundColor: '#EDF4FF',
                            textColor: '#7AA3E5'
                        },
                        {
                            backgroundColor: '#FAF2DD',
                            textColor: '#E8C053'
                        },
                        {
                            backgroundColor: '#FFF2FF',
                            textColor: '#DC76E0'
                        },
                        {
                            backgroundColor: '#FFF0EA',
                            textColor: '#E68B69'
                        }
                    ];

                    // Track colors used by date to avoid duplicates on the same day
                    if (!window.usedColorsByDate) {
                        window.usedColorsByDate = {};
                    }

                    // Get the event's date (YYYY-MM-DD format)
                    const eventDate = new Date(info.event.start).toISOString().split('T')[0];

                    // Initialize color tracking for the date if not present
                    if (!window.usedColorsByDate[eventDate]) {
                        window.usedColorsByDate[eventDate] = [];
                    }

                    // Get available color pairs that have not been used for this date
                    const availableColorPairs = colorPairs.filter(pair => {
                        return !window.usedColorsByDate[eventDate].includes(pair
                            .backgroundColor);
                    });

                    // Reset the colors if all have been used for the date
                    if (availableColorPairs.length === 0) {
                        window.usedColorsByDate[eventDate] = [];
                        availableColorPairs.push(...colorPairs);
                    }

                    // Select a color pair from the available ones
                    const selectedPair = availableColorPairs[Math.floor(Math.random() *
                        availableColorPairs.length)];

                    // Mark the color pair as used for the date
                    window.usedColorsByDate[eventDate].push(selectedPair.backgroundColor);

                    // Store the color for the event if not already set
                    if (!eventColorMap.has(info.event.id)) {
                        eventColorMap.set(info.event.id, selectedPair);
                    }

                    // Apply the colors without affecting the element's margin or size
                    info.el.style.backgroundColor = selectedPair.backgroundColor;
                    info.el.style.color = selectedPair.textColor;

                    // Restore the element's original margin
                    info.el.style.margin = '3px 8px';

                    // Handle event dot color (FullCalendar's dot in the day view)
                    const eventDot = info.el.querySelector('.fc-daygrid-event-dot');
                    if (new Date(info.event.start) <= new Date()) {
                        // For past events
                        info.el.style.backgroundColor =
                            'transparent'; // Transparent for past events
                        info.el.style.color = selectedPair.textColor; // Retain text color
                        if (eventDot) {
                            eventDot.style.borderColor = selectedPair.textColor;
                        }
                    } else {
                        // For future events
                        if (eventDot) {
                            eventDot.style.borderColor = selectedPair.textColor;
                        }
                    }
                },
                viewDidMount: function(info) {
                    // Ensure the select element is created for every day, not just the event days
                    let allDayCells = document.querySelectorAll(
                        '.fc-daygrid-day'); // Get all days in the current view

                    allDayCells.forEach(dayCell => {
                        // console.log(dayCell);
                        createSelectElement(); // Add a select element to each day
                    });
                },
                initialView: 'dayGridMonth',
                locale: 'it',
                events: @json($events),
                headerToolbar: false,
            });
            calendar.render();
            updateCurrentMonth(); // Initial month update
        }

        createCalendar();

        function updateCurrentMonth() {
            var currentMonth = calendar.view.title;
            document.getElementById('current-month').textContent = currentMonth;
        }
        let selectedMechanicId;
        const mechanicSelect = document.getElementById('mechanic-filter');
        if (mechanicSelect) {
            mechanicSelect.addEventListener('change', function() {
                selectedMechanicId = this.value;
                filterEventsByMechanic(selectedMechanicId);
            });
        }

        // Filter events based on the selected mechanic
        function filterEventsByMechanic(mechanicId) {
            var allEvents = calendar.getEvents();

            allEvents.forEach(event => {
                var hasMechanic = event.extendedProps.mechanics.some(mechanic => mechanic.id ==
                    mechanicId);

                if (mechanicId === "" || hasMechanic) {
                    event.setProp('display', ''); // Show event
                } else {
                    event.setProp('display', 'none'); // Hide event
                }

                var eventElement = eventElementMap.get(event.id);


                if (eventElement) {
                    eventElement.classList.remove('fc-h-event');

                    const eventText = eventElement.querySelector('.fc-event-main-frame');
                    if (eventText) {
                        eventText.classList.add('flex', 'place-items-center', 'mx-2', 'rounded-smm',
                            'px-2');
                        const assignedPair = eventColorMap.get(event.id);
                        if (assignedPair) {
                            eventText.style.color = assignedPair.textColor; // Apply existing text color
                            if (new Date(event.start) >= new Date()) {
                                eventText.style.backgroundColor = assignedPair
                                    .backgroundColor; // Use assigned bg color
                            }
                        }
                    }

                    const eventDot = eventElement.querySelector('.fc-daygrid-event-dot');
                    if (eventDot) {
                        eventDot.style.borderColor = eventColorMap.get(event.id)
                            ?.textColor; // Set the dot color to match
                    }
                } else {
                    console.warn('Event element not found for event:', event);
                }
            });
        }

        document.getElementById('prev-month').addEventListener('click', function() {
            calendar.prev();
            updateCurrentMonth();
            updateScheduleTable();
            createSelectElement();
            if (selectedMechanicId) {
                filterEventsByMechanic(selectedMechanicId);
            }
        });

        document.getElementById('next-month').addEventListener('click', function() {
            calendar.next();
            updateCurrentMonth();
            updateScheduleTable(); // Update the schedule table when navigating months
            createSelectElement();

            if (selectedMechanicId) {
                filterEventsByMechanic(selectedMechanicId);
            }
        });

        document.getElementById('today').addEventListener('click', function() {
            calendar.today();
            updateCurrentMonth();
            updateScheduleTable(); // Update the schedule table when navigating months
        });

        // Function to show all days of the current month in the schedule
        function getDaysInMonth(year, month) {
            let date = new Date(year, month, 1);
            let days = [];

            while (date.getMonth() === month) {
                days.push(new Date(date));
                date.setDate(date.getDate() + 1);
            }

            return days;
        }

        function updateScheduleTable() {
            let tableBody = document.querySelector('#schedule-section tbody');
            tableBody.innerHTML = ''; // Pulisce le righe esistenti

            // Ottenere il mese e l'anno correnti da FullCalendar
            let currentDate = calendar.getDate();
            let currentYear = currentDate.getFullYear();
            let currentMonth = currentDate.getMonth(); // 0-indexed (0 = January, 11 = December)

            // Ottenere tutti i giorni del mese corrente
            let daysInMonth = getDaysInMonth(currentYear, currentMonth);

            // Usa JSON-encoded availabilities direttamente da Laravel
            const availabilities = @json($availabilities); // Availabilities passed from backend
            const mechanics = @json($mechanics); // Mechanics passed from backend
            const customers = @json($customers); // Customers passed from backend

            // Crea una mappa delle disponibilità per data
            var availabilitiesByDate = {};
            availabilities.forEach(availability => {
                var availabilityDate = availability.date; // 'YYYY-MM-DD' dal backend

                if (!availabilitiesByDate[availabilityDate]) {
                    availabilitiesByDate[availabilityDate] = [];
                }
                availabilitiesByDate[availabilityDate].push(availability);
            });

            // Iterare su tutti i giorni del mese
            daysInMonth.forEach(date => {
                let day = String(date.getDate()).padStart(2, '0');
                let month = String(date.getMonth() + 1).padStart(2, '0');
                let year = date.getFullYear();
                let originalFormattedDate = `${year}-${month}-${day}`; // 'YYYY-MM-DD'

                // Formattare la data come DD/MM/YYYY per la visualizzazione nella tabella
                let formattedDate = `${day}/${month}/${year}`;

                // Controllare se il giorno è un weekend
                let dayOfWeek = new Date(year, date.getMonth(), date.getDate()).toLocaleDateString(
                    'it-IT', {
                        weekday: 'long'
                    });
                if (dayOfWeek === "sabato" || dayOfWeek === "domenica") {
                    return; // Salta i weekend
                }

                let tr = document.createElement('tr');

                // Aggiungi classi appropriate per i venerdì
                if (dayOfWeek === "venerdì") {
                    tr.classList.add('border-dashed', 'border', 'border-b-[#2626B4FF]');
                } else {
                    tr.classList.add('border-b', 'border-b-[#E4E4F7]');
                }

                // Crea una funzione per generare celle
                const createCell = (content, cellClasses = ['border-x']) => {
                    let td = document.createElement('td');
                    td.textContent = content;
                    cellClasses.forEach(cls => td.classList.add(cls));
                    return td;
                };

                // Crea e aggiungi le celle
                //Matteo Denni 6/02/2025 aggiunta delle classi , 'sticky', 'left-0'
                tr.appendChild(createCell(formattedDate, ['border', 'border-dashed',
                    'border-r-[#4453A5]', 'border-b-[#4453A5]', 'px-2', 'py-4',
                    'bg-[#F2F1FB]', 'sticky', 'left-0', 'w-28'
                ]));
                tr.appendChild(createCell(date.toLocaleDateString('it-IT', {
                    weekday: 'long'
                }), ['bg-[#F2F1FB]', 'px-2', 'text-center', 'sticky', 'left-28', 'w-28']));

//Matteo Denni 7/02/2025 barra di scorrimento superiore, è una barra di scorrimento sopra la tabella Programmazione
//prendo il numero dei tecnici e lo moltiplico per la grandezza delle celle
//la grandezza esatta in px era stata calcolata escudendo le prime due colonne, con il risultato di 256px
//se divido la lunghezza della tabella (con 15 tecnici è 4065px) direttamente per il numero dei tecnici ottengo 271px
//con 271px il comportamento dello scroll superiore è migliore
//testato con 200 tecnici, funziona allo stesso modo di 15

  const topScrollbar = document.getElementById('top-scrollbar');
  const tableContainer = document.getElementById('table-container');

  const tableWidth = tableContainer.scrollWidth;
  topScrollbar.querySelector('div').style.width = `${mechanics.length * 271}px`; //viene impostata la lunghezza del div da far scorrere sopra la tabella

  // Sincronizzano le barre di scorrimento superiore e inferiore
  topScrollbar.addEventListener('scroll', function () {
    tableContainer.scrollLeft = topScrollbar.scrollLeft;
  });

  tableContainer.addEventListener('scroll', function () {
    topScrollbar.scrollLeft = tableContainer.scrollLeft;
  });





                // Controlla le disponibilità e popola le celle

                mechanics.forEach(mechanic => {
                    let mechanicAvailabilities = availabilitiesByDate[originalFormattedDate] ||
                        [];
                    let mechanicAvailability = mechanicAvailabilities.find(availability =>
                        availability.mechanic_info_id == mechanic.id);

                    let td = document.createElement('td');
                    td.classList.add('border-x', 'text-center', 'p-2');

                    if (mechanicAvailability) {
                        let state = mechanicAvailability.state;
                        let clientName = mechanicAvailability.client_name;

                        // Crea un select per la selezione del cliente
                        let customerSelect = document.createElement('select');
                        customerSelect.classList.add('w-full', 'border-none', 'bg-[#FAFAFA]',
                            'focus:ring-transparent', 'focus:border-transparent');

                        if (state === 'available') {
                            let defaultOption = document.createElement('option');
                            defaultOption.value = '';
                            defaultOption.textContent = clientName ? clientName : 'Disponibile';
                            customerSelect.style.color = clientName ? 'black' : 'green';
                            customerSelect.appendChild(defaultOption);

                            customers.forEach(customer => {
                                let option = document.createElement('option');
                                option.value = customer.id;
                                option.textContent = customer.user.name + ' ' + customer
                                    .user.surname;
                                customerSelect.appendChild(option);
                            });

                            customerSelect.addEventListener('change', function() {
                                let selectedCustomerId = this.value;

                                fetch('/update-customer', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                    },
                                    body: JSON.stringify({
                                        mechanic_id: mechanic.id,
                                        date: originalFormattedDate,
                                        customer_id: selectedCustomerId // Selected Customer ID
                                    })
                                }).catch(error => console.error('Error:', error));
                            });

                        } else if (state === 'not_available') {
                            let defaultOption = document.createElement('option');
                            defaultOption.value = '';
                            defaultOption.textContent = 'Non disponibile';
                            customerSelect.style.color = '#DC0851';
                            customerSelect.appendChild(defaultOption);
                            customerSelect.disabled = true;
                        }

                        td.appendChild(customerSelect);

                    } else {
                        td.textContent = ''; // Nessuna disponibilità trovata
                    }

                    tr.appendChild(td);
                });

                tableBody.appendChild(tr);
            });
        }



        // Funzione per trovare il <td> più vicino con l'attributo 'data-date'
        function getClosestTdDataDate(element) {
            // Cerca il primo <td> antenato con l'attributo data-date
            const closestTd = element.closest('td[data-date]');

            // Se trova il <td>, restituisce il suo attributo data-date
            if (closestTd) {
                return closestTd.getAttribute('data-date');
            } else {
                // Restituisce null se non trova nessun <td> con data-date
                return null;
            }
        }

        function createSelectElement() {
            @role('mechanic')
                const dayEvents = document.querySelectorAll('.fc-daygrid-day-events');

                dayEvents.forEach(dayEvent => {
                    if (dayEvent && !dayEvent.querySelector('.mechanic-select')) {
                        // Creazione dell'elemento select
                        const selectEl = document.createElement('div');
                        selectEl.classList.add('mechanic-select', 'rounded-sm', 'px-0.5', 'mx-2',
                            'text-[#DC0814]',
                            'text-[15px]', 'cursor-pointer', 'bg-[#FCF5F6]', 'relative');
                        selectEl.textContent = 'Seleziona';
                        dayEvent.insertBefore(selectEl, dayEvent.firstChild);

                        const dataDate = getClosestTdDataDate(selectEl);

                        // Imposta lo stato e lo stile basato sulla disponibilità
                        availabilities.forEach(availability => {
                            if (dataDate === availability.date) {
                                if (availability.state === 'available') {
                                    selectEl.textContent = 'Disponibile';
                                    selectEl.classList.remove('text-[#DC0814]', 'bg-[#FCF5F6]',
                                        'text-[#E4434C]', 'bg-[#F7E5E5]');
                                    selectEl.classList.add('text-[#7FBC4B]', 'bg-[#FAFAFA]');
                                } else if (availability.state === 'not_available') {
                                    selectEl.textContent = 'Non Disponibile';
                                    selectEl.classList.remove('text-[#DC0814]', 'bg-[#FCF5F6]',
                                        'text-[#7FBC4B]', 'bg-[#FAFAFA]');
                                    selectEl.classList.add('text-[#E4434C]', 'bg-[#F7E5E5]');
                                }
                            }
                        });

                        // Funzione per gestire il click sul select
                        selectEl.onclick = function(event) {
                            event.stopPropagation(); // Previene il bubbling dell'evento

                            // Chiude eventuali dropdown aperti
                            const existingDropdown = dayEvent.querySelector('.custom-dropdown');
                            if (existingDropdown) existingDropdown.remove();

                            // Creazione del dropdown con le opzioni
                            const customDropdown = document.createElement('div');
                            customDropdown.classList.add('custom-dropdown', 'z-50', 'bg-white',
                                'w-full', 'absolute', 'top-full', 'mt-1');

                            // Opzioni: Disponibile e Non Disponibile
                            const availableOption = document.createElement('div');
                            availableOption.classList.add('cursor-pointer', 'p-2', 'text-[#7FBC4B]',
                                'hover:bg-[#E7F4E7]');
                            availableOption.textContent = 'Disponibile';

                            const notAvailableOption = document.createElement('div');
                            notAvailableOption.classList.add('cursor-pointer', 'p-2', 'border-y',
                                'text-[#E4434C]', 'hover:bg-[#F7E5E5]');
                            notAvailableOption.textContent = 'Non Disponibile';

                            // Aggiunge le opzioni al dropdown
                            customDropdown.appendChild(availableOption);
                            customDropdown.appendChild(notAvailableOption);
                            dayEvent.appendChild(customDropdown);

                            // Gestisce il click su "Disponibile"
                            availableOption.onclick = function() {
                                const dataDate = getClosestTdDataDate(selectEl);
                                updateMechanicStatus(dataDate, 'available');
                                selectEl.textContent = 'Disponibile';
                                selectEl.classList.remove('text-[#DC0814]', 'bg-[#FCF5F6]',
                                    'text-[#E4434C]', 'bg-[#F7E5E5]');
                                selectEl.classList.add('text-[#7FBC4B]', 'bg-[#FAFAFA]');
                                customDropdown.remove(); // Chiude il dropdown
                            };

                            // Gestisce il click su "Non Disponibile"
                            notAvailableOption.onclick = function() {
                                const dataDate = getClosestTdDataDate(selectEl);
                                updateMechanicStatus(dataDate, 'not_available');
                                selectEl.textContent = 'Non Disponibile';
                                selectEl.classList.remove('text-[#DC0814]', 'bg-[#FCF5F6]',
                                    'text-[#7FBC4B]', 'bg-[#FAFAFA]');
                                selectEl.classList.add('text-[#E4434C]', 'bg-[#F7E5E5]');
                                customDropdown.remove(); // Chiude il dropdown
                            };

                            // Chiude il dropdown cliccando fuori
                            document.addEventListener('click', function closeDropdown(event) {
                                if (!selectEl.contains(event.target) && !customDropdown
                                    .contains(event.target)) {
                                    customDropdown.remove();
                                    document.removeEventListener('click',
                                        closeDropdown); // Rimuove l'evento listener
                                }
                            });
                        };
                    }
                });
            @endrole
        }

        // Function to handle the fetch request for updating mechanic status
        function updateMechanicStatus(date, state) {

            fetch('/update-mechanic-day', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        date: date,
                        state: state
                    })
                })
                .then(response => {

                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        // View toggle between calendar and schedule
        const calendarBtn = document.getElementById('calendar-btn');
        const scheduleBtn = document.getElementById('schedule-btn');
        const calendarSection = document.getElementById('calendar-section');
        const scheduleSection = document.getElementById('schedule-section');
        if (calendarBtn && scheduleBtn) {

            calendarBtn.addEventListener('click', function() {
                toggleView('calendar');
            });

            scheduleBtn.addEventListener('click', function() {
                toggleView('schedule');
            });
        }

        toggleView('calendar');

        function toggleView(selected) {
            if (selected === 'calendar') {
                setTimeout(() => {
                    // updateCurrentMonth();
                    createCalendar();
                }, 100);

                calendarSection.style.display = 'block';
                scheduleSection.style.display = 'none';
                if (calendarBtn && scheduleBtn) {

                    calendarBtn.classList.add('border-b-2', 'border-[#4453A5]', 'text-[#4453A5]',
                        'font-semibold');
                    scheduleBtn.classList.remove('border-b-2', 'border-[#4453A5]', 'text-[#4453A5]',
                        'font-semibold');
                    scheduleBtn.classList.add('text-black');
                }
            } else {

                calendarSection.style.display = 'none';
                scheduleSection.style.display = 'block';

                scheduleBtn.classList.add('border-b-2', 'border-[#4453A5]', 'text-[#4453A5]', 'font-semibold');
                calendarBtn.classList.remove('border-b-2', 'border-[#4453A5]', 'text-[#4453A5]',
                    'font-semibold');
                calendarBtn.classList.add('text-black');

                // Fill schedule dynamically
            }
        }
        updateScheduleTable();

    });


</script>


{{-- qui non troverai lo script per il file pubblico top-scrolling,
perchè con questa tabella il procedimento di creazione del div della
scrollbar è diverso --}}
