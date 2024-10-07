@extends('layout')

@section('content')
    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">
        Calendario</h4>

    <div class="bg-white">
        <div class="flex justify-between px-4 pt-4">
            <div class="flex">
                <button id="prev-month" class="border border-[#E8E8E8] rounded-md h-7 text-center mr-2">Prev</button>
                <button id="next-month" class="border border-[#E8E8E8] rounded-md h-7 text-center mr-4">Next</button>
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

            <div class="mb-4 flex justify-between items-center h-8 px-4">
                <div>
                    <input type="text" class="border border-gray-300 rounded mr-6 h-8 w-[600px]"
                        placeholder="Cerca elemento..." wire:model.debounce.300ms.live="searchTerm" />

                    <select
                        class="pr-12 border border-gray-300 rounded text-gray-600 text-sm h-full leading-none w-[225px]">
                        <option value="">Filtra per stato</option>
                    </select>

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

        function createCalendar() {
            calendar = new FullCalendar.Calendar(calendarEl, {
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
                    info.el.style.backgroundColor = 'transparent'; // Customize background
                    info.el.style.color = '#7AA3E5'; // Customize text color
                },
                initialView: 'dayGridMonth',
                locale: 'it',
                eventColor: '#378006',
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

        document.getElementById('mechanic-filter').addEventListener('change', function() {
            var selectedMechanicId = this.value;
            filterEventsByMechanic(selectedMechanicId);
        });

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

            // Get events for the current view
            var events = calendar.getEvents(); // Events already loaded in the calendar
            var mechanics = @json($mechanics); // Mechanics passed from backend

            var eventsByDate = {};
            events.forEach(event => {
                var eventDate = event.start.toISOString().split('T')[
                    0]; // Convert event date to YYYY-MM-DD
                if (!eventsByDate[eventDate]) eventsByDate[eventDate] = [];
                eventsByDate[eventDate].push(event);

            });

            // Iterate through all days in the month
            daysInMonth.forEach(date => {
                // Original formatted date as YYYY-MM-DD for mechanics
                let originalFormattedDate = date.toISOString().split('T')[
                    0]; // Format date as YYYY-MM-DD

                // Format date as DD/MM/YYYY for the <tr>
                let day = String(date.getDate()).padStart(2,
                    '0'); // Get the day and pad with zero if needed
                let month = String(date.getMonth() + 1).padStart(2,
                    '0'); // Get the month (0-11) and add 1, then pad
                let year = date.getFullYear(); // Get the full year
                let formattedDate = `${day}/${month}/${year}`; // Combine to create DD/MM/YYYY
                // Check the day of the week in Italian
                let dayOfWeek = date.toLocaleDateString('it-IT', {
                    weekday: 'long'
                });

                // If the day is "sabato" or "domenica", skip adding the row
                if (dayOfWeek === "sabato" || dayOfWeek === "domenica") {
                    return; // Skip to the next iteration
                }
                let tr = document.createElement('tr');

                if (dayOfWeek === "venerdì") {
                    tr.classList.add('border-dashed', 'border', 'border-b-[#2626B4FF]');
                } else {
                    tr.classList.add('border-b', 'border-b-[#E4E4F7]');
                }

                // Function to create and style <td> elements
                const createCell = (content, cellClasses = ['border-x']) => {
                    let td = document.createElement('td');
                    td.textContent = content;

                    // Add all classes provided in the cellClasses array
                    cellClasses.forEach(cls => {
                        td.classList.add(cls);
                    });

                    return td;
                };

                // Add Date cell with DD/MM/YYYY format, applying multiple classes
                tr.appendChild(createCell(formattedDate, ['border', // This class applies a border
                    'border-dashed', // This class defines the border style as dashed
                    'border-r-[#4453A5]', // This sets the border color
                    'border-b-[#4453A5]', // This sets the border color
                    'px-2',
                    'py-4',
                    'bg-[#F2F1FB]'
                ]));

                // Add Day of the week cell, applying the same multiple classes
                tr.appendChild(createCell(date.toLocaleDateString('it-IT', {
                    weekday: 'long'
                }), ['bg-[#F2F1FB]', 'px-2', 'text-center'])); // Same classes for the weekday cell

                // Add mechanic availability or empty cells
                mechanics.forEach(mechanic => {
                    let mechanicEvents = eventsByDate[originalFormattedDate] || [];

                    // Check if mechanic event exists for the mechanic
                    let mechanicEvent = mechanicEvents.find(event => {
                        return event.extendedProps.mechanics && event.extendedProps
                            .mechanics.some(m => m.id == mechanic.id);
                    });

                    // Create the cell for mechanic's availability
                    let td = document.createElement('td');
                    td.classList.add('border-x', 'text-center', 'p-3');

                    if (mechanicEvent) {
                        // Find the mechanic's pivot data (confirmed, client_name)
                        let mechanicPivot = mechanicEvent.extendedProps.mechanics.find(m => m
                            .id == mechanic.id);
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
                        // Set the options based on the confirmed state and client_name
                        if (confirmed === 1) {
                            // Mechanic is confirmed
                            // If client_name is present, set it as the default selected option
                            let defaultOption = document.createElement('option');
                            defaultOption.value =
                                ''; // You can set this to something more meaningful if needed
                            defaultOption.textContent = clientName ? clientName :
                                'Disponibile'; // Use client_name or 'Disponibile'
                            defaultOption.style.color = clientName ? 'black' :
                                'green'; // Color based on availability
                            customerSelect.appendChild(defaultOption);

                            // Add an option for each customer
                            @json($customers).forEach(customer => {
                                let option = document.createElement('option');
                                option.value = customer.id;
                                option.textContent = customer.name;
                                customerSelect.appendChild(option);
                            });

                            // Handle change event to update the customer_name in the pivot table via AJAX
                            customerSelect.addEventListener('change', function() {
                                let selectedCustomerId = this.value;
                                console.log(mechanic.id, mechanicEvent.id,
                                    selectedCustomerId);
                                // Make an AJAX request to update the customer_name in the pivot table
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
                            // Mechanic is not available (confirmed is false)
                            // Disable the select dropdown and set "Non disponibile" as the default text
                            let defaultOption = document.createElement('option');
                            defaultOption.value =
                                ''; // Optional: you can set this to something meaningful
                            defaultOption.textContent =
                                'Non disponibile'; // Show "Non disponibile"

                            defaultOption.style.color = 'red'; // Change text color to red
                            customerSelect.appendChild(defaultOption);
                            customerSelect.disabled = true; // Disable the select dropdown

                            // Optionally, you can add an empty option to show customers
                            @json($customers).forEach(customer => {
                                let option = document.createElement('option');
                                option.value = customer.id;
                                option.textContent = customer.name;
                                customerSelect.appendChild(option);
                            });
                        }

                        td.appendChild(customerSelect); // Add the select to the cell

                    } else {
                        // No event found for this mechanic on this date
                        td.textContent = '';
                    }

                    // Append the cell to the row
                    tr.appendChild(td);
                });

                tableBody.appendChild(tr);

            });
        }


        // Mechanic Availability Selection for Mechanic Role
        @role('mechanic')
            const dayGridElements = document.querySelectorAll('.fc-daygrid-day');
            dayGridElements.forEach(dayGridEl => {
                dayGridEl.classList.add('relative');
                let selectEl = document.createElement('div');
                selectEl.classList.add('p-2', 'mx-2', 'text-[#E4434C]', 'cursor-pointer',
                    'bg-[#FAFAFA]');
                selectEl.textContent = 'Seleziona';

                selectEl.onclick = function() {
                    let existingDropdown = dayGridEl.querySelector('.custom-dropdown');
                    if (existingDropdown) {
                        existingDropdown.remove();
                        return;
                    }

                    let customDropdown = document.createElement('div');
                    customDropdown.classList.add('custom-dropdown', 'bg-white', 'absolute',
                        'w-full');

                    let available = document.createElement('div');
                    available.classList.add('p-2', 'cursor-pointer', 'text-[#C9E2B3]');
                    available.textContent = 'Disponibile';

                    let notAvailable = document.createElement('div');
                    notAvailable.classList.add('p-2', 'cursor-pointer', 'text-[#E4434C]');
                    notAvailable.textContent = 'Non disponibile';

                    customDropdown.appendChild(available);
                    customDropdown.appendChild(notAvailable);
                    dayGridEl.appendChild(customDropdown);

                    available.onclick = function() {
                        selectEl.textContent = 'Disponibile';
                        selectEl.classList.remove('text-[#E4434C]', 'text-[#C9E2B3]');
                        selectEl.classList.add('text-[#C9E2B3]');
                        customDropdown.remove();
                        // Send availability to server using AJAX
                    };

                    notAvailable.onclick = function() {
                        selectEl.textContent = 'Non disponibile';
                        selectEl.classList.remove('text-[#E4434C]', 'text-[#C9E2B3]');
                        selectEl.classList.add('text-[#E4434C]');
                        customDropdown.remove();
                        // Send availability to server using AJAX
                    };
                };

                dayGridEl.appendChild(selectEl);
            });
        @endrole

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
                updateScheduleTable();
            }
        }

    });
</script>
