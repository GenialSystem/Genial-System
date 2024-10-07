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

            <div class="mb-4 flex justify-between items-center h-8 px-4">
                <div>
                    <input type="text" class="border border-gray-300 rounded mr-6 h-8 w-[600px]"
                        placeholder="Cerca elemento..." wire:model.debounce.300ms.live="searchTerm" />

                    {{-- <select
                        class="pr-12 border border-gray-300 rounded text-gray-600 text-sm h-full leading-none w-[225px]">
                        <option value="">Filtra per stato</option>
                    </select> --}}
                </div>

            </div>
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
                    info.el.style.backgroundColor = 'transparent'; // Customize background
                    info.el.style.color = '#7AA3E5'; // Customize text color
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
            scheduleSection.innerHTML = ''; // Clear existing tables

            // Get the current month and year from FullCalendar
            let currentDate = calendar.getDate();
            let currentYear = currentDate.getFullYear();
            let currentMonth = currentDate.getMonth(); // 0-indexed (0 = January, 11 = December)

            // Get all the days in the current month
            let daysInMonth = getDaysInMonth(currentYear, currentMonth);

            // Get events for the current view
            var events = calendar.getEvents(); // Events already loaded in the calendar
            var eventsByDate = {};

            events.forEach(event => {
                var eventDate = event.start.toISOString().split('T')[
                    0]; // Convert event date to YYYY-MM-DD
                if (!eventsByDate[eventDate]) eventsByDate[eventDate] = [];
                eventsByDate[eventDate].push(event);
            });

            // Organize days into weeks (Monday to Friday)
            let weeks = [];
            let week = [];

            daysInMonth.forEach(date => {
                let dayOfWeek = date.getDay(); // 0 = Sunday, 6 = Saturday

                // Only include weekdays (Monday to Friday)
                if (dayOfWeek >= 1 && dayOfWeek <= 5) {
                    week.push(date);
                }

                // If it's Friday or the last day of the month, push the week to the weeks array and reset the week array
                if (dayOfWeek === 5 || date.getDate() === daysInMonth[daysInMonth.length - 1]
                    .getDate()) {
                    weeks.push(week);
                    week = [];
                }
            });

            // Iterate through each week to create a separate table for each
            weeks.forEach(week => {
                let table = document.createElement('table');
                table.classList.add('w-full', 'bg-white', 'border', 'border-gray-200', 'rounded-md',
                    'table-fixed', 'mb-4');

                let thead = document.createElement('thead');
                thead.classList.add('bg-[#F5F5F5]', 'text-start');
                let headerRow = document.createElement('tr');

                week.forEach(dayInWeek => {
                    let th = document.createElement('th');
                    th.classList.add('px-2', 'text-[#808080]', 'bg-[#F5F5F5]',
                        'text-[15px]', 'font-normal', 'p-4');
                    let day = String(dayInWeek.getDate()).padStart(2, '0');
                    let month = String(dayInWeek.getMonth() + 1).padStart(2, '0');
                    let year = dayInWeek.getFullYear();
                    th.textContent = `${day}/${month}/${year}`;
                    headerRow.appendChild(th);
                });

                thead.appendChild(headerRow);
                table.appendChild(thead);

                let tbody = document.createElement('tbody');
                let tr = document.createElement('tr');

                week.forEach(dayInWeek => {
                    let td = document.createElement('td');
                    td.classList.add('border', 'border-b-[#E4E4F7]', 'px-2', 'py-4',
                        'text-center');

                    let originalFormattedDate = dayInWeek.toISOString().split('T')[0];

                    // Get mechanic events on this date
                    let mechanicEvents = eventsByDate[originalFormattedDate] || [];

                    // Check if this mechanic has any event on this day
                    let mechanicEvent = mechanicEvents.find(event => {
                        return event.extendedProps.mechanics && event.extendedProps
                            .mechanics.some(m => m.id == mechanic.id);
                    });

                    if (mechanicEvent) {
                        let mechanicPivot = mechanicEvent.extendedProps.mechanics.find(m => m
                            .id == mechanic.id);
                        let confirmed = mechanicPivot.confirmed;
                        let clientName = mechanicPivot.client_name;

                        let customerSelect = document.createElement('select');
                        customerSelect.classList.add('w-full', 'border-none', 'bg-[#FAFAFA]',
                            'focus:ring-transparent', 'focus:border-transparent');

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

                            // Add options for each customer
                            @json($customers).forEach(customer => {

                                let option = document.createElement('option');
                                option.value = customer.id;
                                option.textContent = customer.user.name + ' ' + customer
                                    .user.surname;
                                customerSelect.appendChild(option);
                            });

                            customerSelect.addEventListener('change', function() {
                                let selectedCustomerId = parseInt(this.value,
                                    10); // Ensure this is a number
                                let mechanicId = parseInt(mechanic.id,
                                    10); // Ensure mechanic ID is a number
                                let eventId = parseInt(mechanicEvent.id,
                                    10); // Ensure event ID is a number

                                console.log(mechanicId, eventId,
                                    selectedCustomerId); // Check if these are integers

                                // Make an AJAX request to update the customer_name in the pivot table
                                fetch('/update-customer', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken,
                                        },
                                        body: JSON.stringify({
                                            mechanic_id: mechanicId, // Use integer
                                            event_id: eventId, // Use integer
                                            customer_id: selectedCustomerId // Use integer
                                        })
                                    })
                                    .then(response => response
                                        .json()) // Parse response to JSON
                                    .then(data => {
                                        if (data.success) {

                                            console.log(
                                                'Customer updated successfully');
                                        } else {
                                            // Handle failure: show error message
                                            console.error(
                                                'Failed to update customer');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                    });
                            });


                        } else if (confirmed === 0) {
                            let defaultOption = document.createElement('option');
                            defaultOption.value = '';
                            defaultOption.textContent = 'Non disponibile';
                            defaultOption.style.color = 'red';
                            customerSelect.appendChild(defaultOption);
                            customerSelect.disabled = true;

                            @json($customers).forEach(customer => {
                                let option = document.createElement('option');
                                option.value = customer.id;
                                option.textContent = customer.name;
                                customerSelect.appendChild(option);
                            });
                        }

                        td.appendChild(customerSelect);
                    } else {
                        td.textContent = 'N/A';
                    }

                    tr.appendChild(td);
                });

                tbody.appendChild(tr);
                table.appendChild(tbody);
                scheduleSection.appendChild(table);
            });
        }


        // Call this function after each navigation change
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
