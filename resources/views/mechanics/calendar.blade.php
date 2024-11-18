@extends('layout')

@section('content')
    @livewire('back-button')
    <h2 class="text-[22px] text-[#222222] font-semibold mb-4">
        Calendario - {{ $mechanic->user->name }} {{ $mechanic->user->surname }}</h2>

    <div class="bg-white">
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
                <button id="calendar-btn"
                    class="text-[#4453A5] font-semibold border-b-2 border-[#4453A5] px-2">Calendario</button>
                <button id="schedule-btn" class="text-black px-2 ml-4">Programmazione</button>
            </div>
        </div>

        {{-- Calendar Section --}}
        <div id="calendar-section" class="">
            <div class="border-dashed border my-5"></div>
            <div class="p-4" id="calendar"></div>
        </div>

        {{-- Schedule Section --}}
        <div id="schedule-section" class="py-8 mx-4 hidden">
            <div class="overflow-x-auto">
                <table class="w-full bg-white border border-gray-200 rounded-md table-fixed">
                    <thead class="bg-[#F5F5F5] text-start">
                        <tr>
                            <th
                                class="text-start px-2 text-[#4453A5] bg-[#F2F1FB] text-[15px] font-normal p-4 border border-dashed border-r-[#4453A5] w-32">
                                Data
                            </th>
                            <th class="text-center px-2 text-[#4453A5] bg-[#F2F1FB] text-[15px] font-normal w-32">
                                Giorno
                            </th>

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
        console.log(csrfToken);
        let eventElementMap = new Map();
        const eventColorMap = new Map();

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
                eventClassNames: ['text-[#7AA3E5]', 'bg-transparent', 'text-normal'],
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

                    // Store colors already used for the specific date to avoid duplicates
                    if (!window.usedColorsByDate) {
                        window.usedColorsByDate = {};
                    }

                    // Get event's date (YYYY-MM-DD format)
                    const eventDate = new Date(info.event.start).toISOString().split('T')[0];

                    // Initialize the color tracking for this date if not already done
                    if (!window.usedColorsByDate[eventDate]) {
                        window.usedColorsByDate[eventDate] = [];
                    }

                    // Filter available color pairs that haven't been used yet for this date
                    const availableColorPairs = colorPairs.filter(pair => {
                        return !window.usedColorsByDate[eventDate].includes(pair
                            .backgroundColor);
                    });

                    // If no available pairs, reset used colors to start over (or you could choose to keep it full)
                    if (availableColorPairs.length === 0) {
                        window.usedColorsByDate[eventDate] = [];
                        availableColorPairs.push(...colorPairs);
                    }

                    // Select a random color pair from available ones
                    const selectedPair = availableColorPairs[Math.floor(Math.random() *
                        availableColorPairs.length)];

                    // Mark this color pair as used for the current date
                    window.usedColorsByDate[eventDate].push(selectedPair.backgroundColor);

                    // Set the colors for the event
                    info.el.style.backgroundColor = selectedPair.backgroundColor;
                    info.el.style.color = selectedPair.textColor;
                    info.el.style.margin = '3px 8px';

                    // Adjust the style for the dot element inside the event
                    const eventDot = info.el.querySelector('.fc-daygrid-event-dot');
                    if (new Date(info.event.start) <= new Date()) {
                        // Apply different styles for past events
                        info.el.style.backgroundColor = 'transparent';
                        info.el.style.color = selectedPair.textColor;
                        if (eventDot) {
                            eventDot.style.borderColor = selectedPair.textColor;
                        }
                    } else {
                        // Set the dot color to match the text color for future events
                        if (eventDot) {
                            eventDot.style.borderColor = selectedPair.textColor;
                        }
                    }
                },
                initialView: 'dayGridMonth',
                locale: 'it',
                events: @json($events), // Make sure this contains events from backend
                headerToolbar: false, // Disable default header with buttons
            });
            calendar.render();
            updateCurrentMonth(); // Initial month update
        }

        createCalendar();

        function updateCurrentMonth() {
            var currentMonth = calendar.view.title;
            document.getElementById('current-month').textContent = currentMonth;
        }


        document.getElementById('prev-month').addEventListener('click', function() {
            calendar.prev();
            updateCurrentMonth();
            updateScheduleTable(); // Update the schedule table when navigating months
        });

        document.getElementById('next-month').addEventListener('click', function() {
            calendar.next();
            updateCurrentMonth();
            updateScheduleTable(); // Update the schedule table when navigating months
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
        const mechanic = @json($mechanic);

        function updateScheduleTable() {
            let scheduleSection = document.getElementById('schedule-section');
            scheduleSection.innerHTML = ''; // Pulisce le tabelle esistenti

            // Ottieni il mese e l'anno correnti da FullCalendar
            let currentDate = calendar.getDate();
            let currentYear = currentDate.getFullYear();
            let currentMonth = currentDate.getMonth(); // 0-indexed (0 = Gennaio, 11 = Dicembre)
            let today = new Date().toLocaleDateString('en-CA'); // Formatta oggi come YYYY-MM-DD
            // Ottieni tutti i giorni del mese corrente
            let daysInMonth = getDaysInMonth(currentYear, currentMonth);

            // Ottieni le disponibilità direttamente dal backend
            const availabilities = @json($availabilities); // Disponibilità passate dal backend

            const customers = @json($customers); // Clienti passati dal backend

            // Crea una mappa delle disponibilità per data
            var availabilitiesByDate = {};
            availabilities.forEach(availability => {
                var availabilityDate = availability.date; // 'YYYY-MM-DD' dal backend
                if (!availabilitiesByDate[availabilityDate]) availabilitiesByDate[
                    availabilityDate] = [];
                availabilitiesByDate[availabilityDate].push(availability);
            });

            let weeks = [];
            let week = [];

            daysInMonth.forEach(function(date) {
                let dayOfWeek = date.getDay(); // 0 = Domenica, 6 = Sabato

                if (dayOfWeek >= 1 && dayOfWeek <= 5) {
                    week.push(date);
                }

                // Se è venerdì o l'ultimo giorno del mese, aggiungi la settimana all'array delle settimane e resetta l'array della settimana
                if (dayOfWeek === 5 || date.getDate() === daysInMonth[daysInMonth.length - 1]
                    .getDate()) {
                    weeks.push(week);
                    week = [];
                }
            });

            // Itera attraverso ogni settimana per creare una tabella separata per ciascuna
            weeks.forEach(function(week) {
                let table = document.createElement('table');
                table.classList.add('w-full', 'bg-white', 'border', 'border-gray-200', 'rounded-md',
                    'table-fixed', 'mb-4');

                let thead = document.createElement('thead');
                thead.classList.add('bg-[#F5F5F5]', 'text-start');
                let headerRow = document.createElement('tr');

                // Crea le intestazioni della tabella (giorni della settimana)
                week.forEach(dayInWeek => {
                    let th = document.createElement('th');
                    th.classList.add('px-2', 'text-[#808080]', 'bg-[#F5F5F5]', 'text-[15px]',
                        'font-normal', 'p-4');

                    let day = String(dayInWeek.getDate()).padStart(2, '0');
                    let month = String(dayInWeek.getMonth() + 1).padStart(2, '0');
                    let year = dayInWeek.getFullYear();
                    let formattedDate = `${year}-${month}-${day}`;

                    // Applica il colore blu se il giorno è oggi
                    if (formattedDate === today) {
                        th.style.color = '#4453A5';
                    }

                    th.textContent = `${day}/${month}/${year}`;
                    headerRow.appendChild(th);
                });

                thead.appendChild(headerRow);
                table.appendChild(thead);

                let tbody = document.createElement('tbody');
                let tr = document.createElement('tr');

                week.forEach(dayInWeek => {
                    let td = document.createElement('td');
                    td.classList.add('border', 'border-b-[#E4E4F7]', 'px-2', 'py-2',
                        'text-center');

                    let currentDay = dayInWeek.toLocaleDateString(
                        'en-CA'); // Formato YYYY-MM-DD

                    // Ottieni le disponibilità per il giorno corrente
                    let mechanicAvailabilities = availabilitiesByDate[currentDay] || [];

                    if (mechanicAvailabilities.length > 0) {
                        let mechanicAvailability = mechanicAvailabilities[
                            0]; // Supponendo un meccanico per disponibilità

                        let confirmed = mechanicAvailability.state; // Stato della disponibilità
                        let clientName = mechanicAvailability.client_name;

                        let customerSelect = document.createElement('select');
                        customerSelect.classList.add('w-full', 'border-none', 'bg-[#FAFAFA]',
                            'focus:ring-transparent', 'focus:border-transparent');

                        if (confirmed === 'available') {
                            let defaultOption = document.createElement('option');
                            defaultOption.value = '';
                            defaultOption.textContent = clientName ? clientName : 'Disponibile';
                            defaultOption.style.color = clientName ? 'black' : 'green';
                            customerSelect.appendChild(defaultOption);

                            // Aggiungi opzioni dei clienti
                            customers.forEach(customer => {
                                let option = document.createElement('option');
                                option.value = customer.id;
                                option.textContent = customer.user.name + ' ' + customer
                                    .user.surname;
                                customerSelect.appendChild(option);
                            });

                            // Gestisci l'evento di cambio selezione
                            customerSelect.addEventListener('change', function() {
                                let selectedCustomerId = this.value;
                                // Recupera l'id del meccanico
                                const mechanicId = mechanicAvailability
                                    .mechanic_info_id;
                                // Esegui la richiesta AJAX per aggiornare il cliente
                                fetch('/update-customer', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken,
                                        },
                                        body: JSON.stringify({
                                            mechanic_id: mechanicId,
                                            date: currentDay,
                                            customer_id: selectedCustomerId
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            console.log(
                                                'Customer updated successfully');
                                        } else {
                                            console.error(
                                                'Failed to update customer');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });
                            });
                        } else if (confirmed === 'not_available') {
                            let defaultOption = document.createElement('option');
                            defaultOption.value = '';
                            defaultOption.textContent = 'Non disponibile';
                            customerSelect.style.color = '#DC0851';
                            customerSelect.appendChild(defaultOption);
                            customerSelect.disabled = true;
                        }

                        td.appendChild(customerSelect);
                    } else {
                        td.textContent = 'N/A'; // Nessuna disponibilità trovata
                    }

                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
                table.appendChild(tbody);
                scheduleSection.appendChild(table);
            });
        }


        updateScheduleTable();

        // View toggle between calendar and schedule
        const calendarBtn = document.getElementById('calendar-btn');
        const scheduleBtn = document.getElementById('schedule-btn');
        const calendarSection = document.getElementById('calendar-section');
        const scheduleSection = document.getElementById('schedule-section');

        calendarBtn.addEventListener('click', function() {
            toggleView('calendar');
        });

        scheduleBtn.addEventListener('click', function() {
            toggleView('schedule');
        });

        toggleView('calendar');

        function toggleView(selected) {
            if (selected === 'calendar') {
                setTimeout(() => {
                    // updateCurrentMonth();
                    createCalendar();
                }, 100);

                calendarSection.style.display = 'block';
                scheduleSection.style.display = 'none';

                calendarBtn.classList.add('border-b-2', 'border-[#4453A5]', 'text-[#4453A5]', 'font-semibold');
                scheduleBtn.classList.remove('border-b-2', 'border-[#4453A5]', 'text-[#4453A5]',
                    'font-semibold');
                scheduleBtn.classList.add('text-black');
            } else {

                calendarSection.style.display = 'none';
                scheduleSection.style.display = 'block';

                scheduleBtn.classList.add('border-b-2', 'border-[#4453A5]', 'text-[#4453A5]', 'font-semibold');
                calendarBtn.classList.remove('border-b-2', 'border-[#4453A5]', 'text-[#4453A5]',
                    'font-semibold');
                calendarBtn.classList.add('text-black');

                // Fill schedule dynamically
                // updateScheduleTable();
            }
        }

    });
</script>
