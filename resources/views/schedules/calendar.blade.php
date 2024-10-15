@extends('layout')

@section('content')
    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">
        Calendario</h4>

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
                    <input type="text" class="border border-gray-300 rounded xl:mr-6 h-8 2xl:w-[600px]"
                        placeholder="Cerca elemento..." wire:model.debounce.300ms.live="searchTerm" />

                    {{-- <select
                        class="pr-12 border border-gray-300 rounded text-gray-600 text-sm h-full leading-none w-[225px]">
                        <option value="">Filtra per stato</option>
                    </select> --}}
                    @role('admin')
                        <select id="mechanic-filter"
                            class="pr-12 border border-gray-300 rounded text-gray-600 text-sm h-8 leading-none w-[225px] 2xl:ml-6">
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

        let test;

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
                    @role('mechanic')
                        const filteredEvents = @json($events).filter(event => event
                            .id ==
                            info.event._def.publicId);

                        createSelectElement(info.el, filteredEvents);
                    @endrole
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

        const mechanicSelect = document.getElementById('mechanic-filter');
        if (mechanicSelect) {
            mechanicSelect.addEventListener('change', function() {
                var selectedMechanicId = this.value;
                filterEventsByMechanic(selectedMechanicId);
            });
        }

        // Filter events based on the selected mechanic
        function filterEventsByMechanic(mechanicId) {
            // Get all events from FullCalendar
            var allEvents = calendar.getEvents();

            // Loop through the events and filter based on the mechanicId
            allEvents.forEach(event => {
                var hasMechanic = event.extendedProps.mechanics.some(mechanic => mechanic.id ==
                    mechanicId);

                // Show the event if the mechanic is found, hide it otherwise
                if (mechanicId === "" || hasMechanic) {
                    event.setProp('display', ''); // Show event
                } else {
                    event.setProp('display', 'none'); // Hide event
                }

                // Reapply the custom styles for the event
                var eventElement = event.el; // Get the DOM element for the event
                if (eventElement) {
                    eventElement.style.backgroundColor = '#222222'; // Reapply background
                    eventElement.style.color = '#7AA3E5'; // Reapply text color
                }
            });
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

        function updateScheduleTable() {
            let tableBody = document.querySelector('#schedule-section tbody');
            tableBody.innerHTML = ''; // Clear existing rows

            // Get the current month and year from FullCalendar
            let currentDate = calendar.getDate(); // This gives the date of the currently selected view
            let currentYear = currentDate.getFullYear();
            let currentMonth = currentDate.getMonth(); // 0-indexed (0 = January, 11 = December)

            // Get all the days in the current month
            let daysInMonth = getDaysInMonth(currentYear, currentMonth);

            // Use JSON-encoded events directly from Laravel
            var events = @json($events); // Assuming this is correctly outputting your events
            var mechanics = @json($mechanics); // Mechanics passed from backend

            // Create a mapping of events by date
            var eventsByDate = {};
            events.forEach(event => {
                // Use the raw date string from Laravel (no conversion to Date object)
                var eventDate = event.date; // This is 'YYYY-MM-DD' directly from backend

                if (!eventsByDate[eventDate]) eventsByDate[eventDate] = [];
                eventsByDate[eventDate].push(event);
            });

            // Iterate through all days in the month
            daysInMonth.forEach(date => {
                let day = String(date.getDate()).padStart(2, '0');
                let month = String(date.getMonth() + 1).padStart(2, '0');
                let year = date.getFullYear();
                let originalFormattedDate = `${year}-${month}-${day}`; // This is now 'YYYY-MM-DD'

                // Format date as DD/MM/YYYY for table display
                let formattedDate = `${day}/${month}/${year}`;

                // Check if the day is a weekend and skip if so
                let dayOfWeek = new Date(year, date.getMonth(), date.getDate()).toLocaleDateString(
                    'it-IT', {
                        weekday: 'long'
                    });
                if (dayOfWeek === "sabato" || dayOfWeek === "domenica") {
                    return; // Skip weekends
                }

                let tr = document.createElement('tr');

                // Add appropriate class for Fridays
                if (dayOfWeek === "venerdì") {
                    tr.classList.add('border-dashed', 'border', 'border-b-[#2626B4FF]');
                } else {
                    tr.classList.add('border-b', 'border-b-[#E4E4F7]');
                }
                const createCell = (content, cellClasses = ['border-x']) => {
                    let td = document.createElement('td');
                    td.textContent = content;

                    // Add all classes provided in the cellClasses array
                    cellClasses.forEach(cls => {
                        td.classList.add(cls);
                    });

                    return td;
                };
                // Create and append cells
                tr.appendChild(createCell(formattedDate, ['border', 'border-dashed',
                    'border-r-[#4453A5]', 'border-b-[#4453A5]', 'px-2', 'py-4',
                    'bg-[#F2F1FB]'
                ]));
                tr.appendChild(createCell(date.toLocaleDateString('it-IT', {
                    weekday: 'long'
                }), ['bg-[#F2F1FB]', 'px-2', 'text-center']));

                // Check for mechanic events and populate cells
                mechanics.forEach(mechanic => {
                    let mechanicEvents = eventsByDate[originalFormattedDate] || [];
                    let mechanicEvent = mechanicEvents.find(event => {
                        return event.mechanics && event.mechanics.some(m => m.id ==
                            mechanic.id);
                    });

                    // Create the cell for the mechanic's availability
                    let td = document.createElement('td');
                    td.classList.add('border-x', 'text-center', 'p-3');

                    if (mechanicEvent) {
                        // Find the mechanic's pivot data (confirmed, client_name)
                        let mechanicPivot = mechanicEvent.mechanics.find(m => m.id == mechanic
                            .id);
                        let confirmed = mechanicPivot.confirmed;
                        let clientName = mechanicPivot.client_name; // Get client_name

                        // Create a select dropdown for customer selection
                        let customerSelect = document.createElement('select');
                        customerSelect.classList.add('w-full', 'border-none',
                            'bg-[#FAFAFA]', 'focus:ring-transparent',
                            'focus:border-transparent'
                        ); // Add basic styling
                        if (confirmed === null && clientName === null) {
                            td.textContent = '';
                            return;
                        }

                        if (confirmed === 1) {
                            let defaultOption = document.createElement('option');
                            defaultOption.value = '';
                            defaultOption.textContent = clientName ? clientName : 'Disponibile';
                            defaultOption.style.color = clientName ? 'black' : 'green';
                            customerSelect.appendChild(defaultOption);

                            @json($customers).forEach(customer => {
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
                                            event_id: mechanicEvent.id,
                                            customer_id: selectedCustomerId // Selected Customer ID
                                        })
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });
                            });

                        } else if (confirmed === 0) {
                            let defaultOption = document.createElement('option');
                            defaultOption.value = '';

                            defaultOption.textContent = mechanicEvent.date;
                            defaultOption.style.color = 'red';
                            customerSelect.appendChild(defaultOption);
                            customerSelect.disabled = true;

                            @json($customers).forEach(customer => {
                                let option = document.createElement('option');
                                option.value = customer.id;
                                option.textContent = customer.user.name + ' ' + customer
                                    .user.surname;
                                customerSelect.appendChild(option);
                            });
                        }

                        td.appendChild(customerSelect);

                    } else {
                        td.textContent = '';
                    }

                    tr.appendChild(td);
                });

                tableBody.appendChild(tr);
            });
        }

        function createSelectElement(dayGridEl, mechanicEvents) {
            mechanicEvents.forEach(mechanicEvent => {
                let existingSelect = dayGridEl.querySelector('.mechanic-select');
                let selectEl;

                if (existingSelect) {
                    selectEl = existingSelect; // Use the existing select element if present
                } else {
                    // Create a new select element if it doesn't exist
                    selectEl = document.createElement('div');
                    selectEl.classList.add('mechanic-select', 'rounded-sm', 'px-0.5', 'mx-2',
                        'text-[#DC0814]', 'text-[15px]', 'cursor-pointer', 'bg-[#FCF5F6]',
                        'relative');
                    dayGridEl.parentNode.insertBefore(selectEl, dayGridEl);
                }

                let statusText = 'Seleziona'; // Default text
                let confirmed = null; // default state

                // Ensure there is at least one mechanic in the event's mechanics array
                if (mechanicEvent.mechanics && mechanicEvent.mechanics.length > 0) {
                    let firstMechanic = mechanicEvent.mechanics[0];
                    confirmed = firstMechanic.confirmed; // Get confirmed status

                    // Determine status text and update select element style
                    if (confirmed === 1) {
                        statusText = 'Disponibile';
                        selectEl.classList.remove('text-[#DC0814]', 'bg-[#FCF5F6]');
                        selectEl.classList.add('text-[#7FBC4B]', 'bg-[#FAFAFA]'); // Available styles
                    } else if (confirmed === 0) {
                        statusText = 'Non Disponibile';
                        selectEl.classList.remove('text-[#DC0814]', 'bg-[#FCF5F6]');
                        selectEl.classList.add('text-[#E4434C]',
                            'bg-[#FCF5F6]'); // Not available styles
                    } else {
                        selectEl.classList.add('text-[#DC0814]', 'bg-[#FCF5F6]'); // Default styling
                    }
                }

                selectEl.textContent = statusText;

                // Attach event listener for dropdown
                selectEl.onclick = function() {
                    // Remove any existing dropdown
                    let existingDropdown = dayGridEl.querySelector('.custom-dropdown');
                    if (existingDropdown) {
                        existingDropdown.remove();
                        return;
                    }

                    // Create dropdown
                    let customDropdown = document.createElement('div');
                    customDropdown.classList.add('custom-dropdown', 'z-50', 'bg-white', 'w-full',
                        'absolute', 'top-0');

                    // Create "Disponibile" option
                    let available = document.createElement('div');
                    available.classList.add('cursor-pointer', 'p-2', 'text-[#7FBC4B]',
                        'hover:bg-[#E7F4E7]');
                    available.textContent = 'Disponibile';

                    // Create "Non Disponibile" option
                    let notAvailable = document.createElement('div');
                    notAvailable.classList.add('cursor-pointer', 'p-2', 'border-y',
                        'text-[#E4434C]', 'hover:bg-[#F7E5E5]');
                    notAvailable.textContent = 'Non Disponibile';

                    // Append options to dropdown
                    customDropdown.appendChild(available);
                    customDropdown.appendChild(notAvailable);
                    dayGridEl.appendChild(customDropdown);

                    // Handle click for "Disponibile"
                    available.onclick = function() {
                        // Set confirmed to 1 and update UI immediately
                        updateMechanicStatus(mechanicEvent.id, 1);
                        selectEl.textContent = 'Disponibile';
                        selectEl.classList.remove('text-[#DC0814]', 'bg-[#FCF5F6]',
                            'text-[#E4434C]', 'bg-[#F7E5E5]');
                        selectEl.classList.add('text-[#7FBC4B]', 'bg-[#FAFAFA]');
                        customDropdown.remove();
                    };

                    // Handle click for "Non Disponibile"
                    notAvailable.onclick = function() {
                        // Set confirmed to 0 and update UI immediately
                        updateMechanicStatus(mechanicEvent.id, 0);
                        selectEl.textContent = 'Non Disponibile';
                        selectEl.classList.remove('text-[#DC0814]', 'bg-[#FCF5F6]',
                            'text-[#7FBC4B]', 'bg-[#FAFAFA]');
                        selectEl.classList.add('text-[#E4434C]', 'bg-[#F7E5E5]');
                        customDropdown.remove();
                    };
                };
            });
        }


        // Function to handle the fetch request for updating mechanic status
        function updateMechanicStatus(eventId, confirmed) {
            fetch('/update-mechanic-day', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        event_id: eventId,
                        confirmed: confirmed // Set confirmed status
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
