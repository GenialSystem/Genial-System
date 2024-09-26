<div class="w-1/2 h-80 bg-white p-4 mt-4 rounded-md">
    <div class="flex flex-wrap justify-between">
        <h6 class="text-[#222222] text-lg font-semibold">Panoramica vendite</h6>
        <div id="dateInputSalesChart" class="relative">
            <!-- Date Range Input -->
            <div class="border-[#E0E0E0] border flex justify-between items-center h-8 w-72 rounded-md cursor-pointer">
                <span id="dateDisplaySalesChart" class="ml-2 text-[#9F9F9F] flex items-center">Seleziona il periodo</span>
                <div class="bg-[#E8E8E8] rounded-r w-8 h-full flex justify-center items-center">
                    <img src="{{ asset('images/calendar icon.svg') }}" class="w-3" alt="calendar icon">
                </div>
            </div>

            <!-- Calendar Modal -->
            <div id="calendarModalSalesChart"
                class="hidden absolute bg-white border border-gray-300 rounded-lg shadow-lg w-64 mt-2 z-50">
                <div class="p-4">
                    <!-- New Month Selector -->
                    <select wire:ignore id="startMonth" class="w-full mb-2 border rounded-md p-2">
                        <option value="">Seleziona mese di inizio</option>
                        <!-- Add months dynamically -->
                    </select>
                    <select wire:ignore id="endMonth" class="w-full border rounded-md p-2">
                        <option value="">Seleziona mese di fine</option>
                        <!-- Add months dynamically -->
                    </select>

                    <button id="applyPeriod" class="mt-2 bg-[#282465] text-white px-2 py-2 rounded">Applica</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore id="salesSummary" class="flex flex-wrap">
        <!-- Spans for sales data will be added here -->
    </div>
    <!-- Canvas for Chart.js -->
    <div class="mt-4">
        <canvas id="salesChart" height="200"></canvas>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let chart;
            const ctx = document.getElementById('salesChart').getContext('2d');
            const dateInputSalesChart = document.getElementById('dateInputSalesChart');
            const calendarModalSalesChart = document.getElementById('calendarModalSalesChart');
            const dateDisplaySalesChart = document.getElementById('dateDisplaySalesChart');
            const applyPeriod = document.getElementById('applyPeriod');
            const startMonth = document.getElementById('startMonth');
            const endMonth = document.getElementById('endMonth');
            const salesSummaryDiv = document.getElementById('salesSummary');

            const monthNames = {
                '01': 'Gennaio',
                '02': 'Febbraio',
                '03': 'Marzo',
                '04': 'Aprile',
                '05': 'Maggio',
                '06': 'Giugno',
                '07': 'Luglio',
                '08': 'Agosto',
                '09': 'Settembre',
                '10': 'Ottobre',
                '11': 'Novembre',
                '12': 'Dicembre'
            };

            // Add months dynamically
            const monthOptions = Object.entries(monthNames).map(([key, value]) =>
                `<option value="${key}">${value}</option>`).join('');
            startMonth.innerHTML += monthOptions;
            endMonth.innerHTML += monthOptions;

            dateInputSalesChart.addEventListener('click', function() {
                calendarModalSalesChart.classList.remove('hidden');
            });

            applyPeriod.addEventListener('click', function() {
                const start = startMonth.value;
                const end = endMonth.value;

                if (start && end) {
                    const displayStart = monthNames[start];
                    const displayEnd = monthNames[end];
                    dateDisplaySalesChart.textContent = `${displayStart} - ${displayEnd}`;
                    calendarModalSalesChart.classList.add('hidden');

                    Livewire.dispatch('updateSalesChart', {
                        start: start,
                        end: end
                    });
                }
            });

            Livewire.on('salesChartUpdated', function(data) {
                if (Array.isArray(data) && data.length > 0) {
                    const receivedData = data[0];

                    if (receivedData && receivedData.salesData && receivedData.periods) {
                        const salesData = receivedData.salesData;
                        const periods = receivedData.periods;

                        const monthLabels = Object.keys(salesData).map(month => {
                            const [year, monthNumber] = month.split('-');
                            return `${monthNames[monthNumber]} ${year}`;
                        });

                        const datasets = monthLabels.map((month, index) => ({
                            label: `Sales ${month} (€)`,
                            data: salesData[Object.keys(salesData)[index]],
                            borderColor: getRandomColor(),
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: 'transparent',
                            pointBorderColor: '#00000',
                            borderWidth: 1.5
                        }));

                        updateChart(periods, datasets);
                        updateSalesSummary(monthLabels, salesData)
                    } else {
                        console.error('Unexpected data format received from Livewire:', receivedData);
                    }
                } else {
                    console.error('Unexpected data format received from Livewire:', data);
                }
            });

            function updateChart(periods, datasets) {
                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: periods,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#9F9F9F',
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                min: 0,
                                max: 100000,
                                ticks: {
                                    stepSize: 25000,
                                    callback: function(value) {
                                        return (value / 1000) + 'k';
                                    }
                                },
                                grid: {
                                    color: '#ddd',
                                    borderDash: [12, 12]
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return '€' + (context.raw / 1000).toFixed(1) + 'k';
                                    }
                                }
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }

            function updateSalesSummary(monthLabels, salesData) {
                salesSummaryDiv.innerHTML = ''; // Clear existing spans

                Object.keys(salesData).forEach((key, index) => {
                    const container = document.createElement('div');
                    container.className = 'flex items-center mb-1';

                    const circle = document.createElement('span');
                    circle.classList.add('w-3', 'h-3', 'mr-1', 'rounded-full');
                    circle.style.backgroundColor = chart?.data?.datasets[index]?.borderColor || '#000';

                    const text = document.createElement('span');
                    text.textContent = monthLabels[index];
                    text.classList.add('text-[#222222]', 'text-[12px]', 'mr-4');

                    container.appendChild(circle);
                    container.appendChild(text);
                    salesSummaryDiv.appendChild(container);
                });
            }

            const initialData = @json($initialData);
            if (initialData && initialData.salesData && initialData.periods) {
                const salesData = initialData.salesData;
                const periods = initialData.periods;
                const initialMonths = Object.keys(salesData);

                const initialMonthLabels = initialMonths.map(month => {
                    const [year, monthNumber] = month.split('-');
                    return `${monthNames[monthNumber]} ${year}`;
                });

                const initialDatasets = initialMonths.map((month, index) => ({
                    label: `Sales ${initialMonthLabels[index]} (€)`,
                    data: salesData[month],
                    borderColor: getRandomColor(),
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'transparent',
                    pointBorderColor: '#00000',
                    borderWidth: 1.5
                }));

                updateChart(periods, initialDatasets);
                updateSalesSummary(initialMonthLabels, salesData);
            }

            function getRandomColor() {
                const letters = '0123456789ABCDEF';
                let color = '#';
                for (let i = 0; i < 6; i++) {
                    color += letters[Math.floor(Math.random() * 16)];
                }
                return color;
            }
        });
    </script>
</div>
