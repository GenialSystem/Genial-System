<div class="z-40">
    <div id="dateInput"
        class="border-[#E0E0E0] border flex justify-between place-content-center h-8 w-56 rounded-md cursor-pointer">
        <span id="dateDisplay" class="ml-2 text-[#9F9F9F] flex place-items-center">{{ $dateFilter ?: 'GG/MM/AA' }}</span>
        <div class="bg-[#E8E8E8] rounded-r w-8 flex justify-center place-items-center">

            <img src="{{ asset('images/calendar icon.svg') }}" class="w-3" alt="calendar icon">
        </div>
    </div>

    <!-- The calendar (hidden by default) -->
    <div id="calendar" class="calendar hidden absolute bg-white border border-gray-300 rounded-lg shadow-lg w-96"></div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const dateInput = document.getElementById('dateInput');
        const dateDisplaySpan = document.getElementById('dateDisplay');
        const calendar = document.getElementById('calendar');
        let today = new Date();
        let currentMonth = today.getMonth();
        let currentYear = today.getFullYear();

        // Show/Hide Calendar on click
        dateInput.addEventListener('click', (event) => {
            event.stopPropagation();
            calendar.classList.toggle('hidden');
            generateCalendar();
        });

        // Close the calendar if clicking outside
        document.addEventListener('click', (event) => {
            if (!event.target.closest('.z-50')) {
                calendar.classList.add('hidden');
            }
        });

        function generateCalendar() {
            calendar.innerHTML = ''; // Clear previous calendar content

            const header = document.createElement('div');
            header.className = 'flex items-center justify-between p-2 bg-gray-200 border-b border-gray-300';

            const prevMonthBtn = document.createElement('button');
            prevMonthBtn.id = 'prevMonth';
            prevMonthBtn.className = 'p-1 text-gray-600 hover:bg-gray-300 rounded';
            prevMonthBtn.textContent = '←';

            const monthYearSpan = document.createElement('span');
            monthYearSpan.className = 'font-bold';
            monthYearSpan.textContent = `${getMonthName(currentMonth)} ${currentYear}`;

            const nextMonthBtn = document.createElement('button');
            nextMonthBtn.id = 'nextMonth';
            nextMonthBtn.className = 'p-1 text-gray-600 hover:bg-gray-300 rounded';
            nextMonthBtn.textContent = '→';

            header.appendChild(prevMonthBtn);
            header.appendChild(monthYearSpan);
            header.appendChild(nextMonthBtn);
            calendar.appendChild(header);

            const table = document.createElement('table');
            table.className = 'table w-full';

            const thead = document.createElement('thead');
            const tbody = document.createElement('tbody');

            const daysOfWeek = ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];
            const theadRow = document.createElement('tr');
            daysOfWeek.forEach(day => {
                const th = document.createElement('th');
                th.textContent = day;
                theadRow.appendChild(th);
            });
            thead.appendChild(theadRow);

            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

            let tbodyRow = document.createElement('tr');
            for (let i = 0; i < firstDay; i++) {
                tbodyRow.appendChild(document.createElement('td'));
            }

            for (let day = 1; day <= daysInMonth; day++) {
                if ((firstDay + day - 1) % 7 === 0 && day !== 1) {
                    tbody.appendChild(tbodyRow);
                    tbodyRow = document.createElement('tr');
                }
                const td = document.createElement('td');
                td.className = 'py-2 hover:bg-gray-200 cursor-pointer text-center';
                td.dataset.date = day;
                td.textContent = day;
                tbodyRow.appendChild(td);
            }
            tbody.appendChild(tbodyRow);
            table.appendChild(thead);
            table.appendChild(tbody);
            calendar.appendChild(table);

            // Month navigation events
            prevMonthBtn.addEventListener('click', (event) => {
                event.stopPropagation();
                currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
                if (currentMonth === 11) currentYear--;
                generateCalendar();
            });

            nextMonthBtn.addEventListener('click', (event) => {
                event.stopPropagation();
                currentMonth = (currentMonth === 11) ? 0 : currentMonth + 1;
                if (currentMonth === 0) currentYear++;
                generateCalendar();
            });

            // Date selection event
            document.querySelectorAll('#calendar td').forEach(td => {
                td.addEventListener('click', (event) => {
                    const selectedDate = event.target.dataset.date;
                    if (selectedDate) {
                        const formattedDate =
                            `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(selectedDate).padStart(2, '0')}`;
                        // Update the Livewire property, which updates the span
                        Livewire.dispatch('updateDate', {
                            date: formattedDate
                        });
                        // Hide the calendar
                        calendar.classList.add('hidden');
                    }
                });
            });
        }

        // Helper function to get the month name
        function getMonthName(monthIndex) {
            return new Date(0, monthIndex).toLocaleString('it', {
                month: 'long'
            });
        }
    });
</script>
