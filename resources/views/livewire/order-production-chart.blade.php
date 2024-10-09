<div class="2xl:w-1/2 h-80 bg-white p-4 mt-4 rounded-md">
    <div class="flex justify-between">
        <h6 class="text-[#222222] text-lg font-semibold">Panoramica produzione</h6>
        <div class="relative">
            <!-- Date Input -->
            <div id="dateInput"
                class="border-[#E0E0E0] border flex justify-between items-center h-8 w-56 rounded-md cursor-pointer">
                <span id="dateDisplay" class="ml-2 text-[#9F9F9F] flex items-center">{{ $selectedYear }}</span>
                <div class="bg-[#E8E8E8] rounded-r w-8 h-full flex justify-center items-center">
                    <img src="{{ asset('images/calendar icon.svg') }}" class="w-3" alt="calendar icon">
                </div>
            </div>

            <!-- Calendar Modal -->
            <div id="calendarModal"
                class="hidden absolute bg-white border border-gray-300 rounded-lg shadow-lg w-48 mt-2 z-50">
                <div class="p-4">
                    <select id="yearSelect" wire:model="selectedYear"
                        class="w-full bg-white border border-gray-300 rounded-md p-2">
                        @foreach (range(2020, \Carbon\Carbon::now()->year) as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Canvas for Chart.js -->
    <div class="mt-4">
        <canvas id="orderProductionChart" height="240"></canvas>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let chart;
            const ctx = document.getElementById('orderProductionChart').getContext('2d');

            // Get current month (1 for January, 12 for December)
            const currentMonth = new Date().getMonth() + 1; // JavaScript months are zero-indexed (0-11), so add 1

            function updateChart(months, orderCounts) {
                if (chart) {
                    chart.destroy(); // Destroy existing chart instance
                }

                // Set different colors: one for the current month, another for the rest
                const backgroundColors = months.map((month, index) => {
                    return (index + 1 === currentMonth) ? '#282465' :
                        '#F2F1FB';
                });

                const labelColors = months.map((month, index) => {
                    return (index + 1 === currentMonth) ? '#FFFFFFFF' :
                        '#48449A';
                });

                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: months, // Months in Italian
                        datasets: [{
                            data: orderCounts, // Order counts
                            backgroundColor: backgroundColors, // Apply the dynamic background colors
                            borderColor: 'transparent',
                            borderWidth: 1,
                            borderRadius: 6,
                            barThickness: 50,
                            categoryPercentage: 1,
                            barPercentage: 1
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + context.raw;
                                    }
                                }
                            },
                            datalabels: {
                                display: true,
                                color: labelColors,
                                anchor: 'end',
                                align: 'start',
                                formatter: (value) => value,
                                font: {
                                    weight: 'bold'
                                },
                                padding: {
                                    top: 4
                                }
                            }
                        },
                        scales: {
                            x: {
                                position: 'top',
                                border: {
                                    display: false,
                                },
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    display: true,
                                    color: '#9F9F9F',
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            y: {
                                border: {
                                    display: false,
                                },
                                grid: {
                                    display: true,
                                    color: '#ddd',
                                    borderDash: [0.2, 0.2]
                                },
                                ticks: {
                                    display: false,
                                    stepSize: 10,
                                    callback: function(value, index, values) {
                                        if (index % 2 === 0) {
                                            return value;
                                        }
                                    }
                                }
                            }
                        },
                        responsive: true,
                        maintainAspectRatio: false
                    },
                    plugins: [ChartDataLabels] // Ensure you include the data labels plugin
                });
            };

            // Initialize chart with default data
            updateChart(@json($months), @json($orderCounts));

            // Listen for Livewire updates


            // Toggle the modal
            document.getElementById('dateInput').addEventListener('click', function() {
                document.getElementById('calendarModal').classList.toggle('hidden');
            });

            // Handle year selection
            document.getElementById('yearSelect').addEventListener('change', function() {
                // Update the year in Livewire and trigger the update
                Livewire.dispatch('updateYear', {
                    year: this.value
                });
                Livewire.on('chartDataUpdated', (data) => {
                    console.log(data[0].months, data[0].orderCounts, this.value);
                    updateChart(data[0].months, data[0].orderCounts);
                });
                document.getElementById('dateDisplay').textContent = this.value;
                document.getElementById('calendarModal').classList.add('hidden');
            });
        });
    </script>

</div>
