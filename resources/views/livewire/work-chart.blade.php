<div class="2xl:w-2/5 rounded-lg mt-4 bg-white p-6 w-full h-full"> <!-- Set a fixed height -->
    <h3 class="text-[#222222] font-semibold text-lg mb-4">Andamento lavori</h3>
    <div class="h-[400px] flex"> <!-- Use flex to allow the canvas to fill space -->
        <canvas id="carCountChart"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pass the data from PHP to JavaScript
        const orderCounts = @json($orderCounts);
        const labels = @json($labels);

        // Create the Chart
        const ctx = document.getElementById('carCountChart').getContext('2d');
        const carCountChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // Use the labels from PHP
                datasets: [{
                    pointBackgroundColor: 'transparent',
                    data: orderCounts, // Use the order counts
                    borderColor: '#FFCD5D',
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    pointRadius: 5,
                    fill: true
                }]
            },
            options: {
                scales: {
                    x: {
                        grid: {
                            display: false // Hide grid lines
                        },
                        ticks: {
                            color: '#9F9F9F', // Set tick color for the x-axis
                            font: {
                                size: 11 // Set font size for ticks
                            }
                        }
                    },
                    y: {
                        min: 0,
                        max: 100, // Set the max for Y-axis
                        ticks: {
                            stepSize: 25, // Step size for Y-axis ticks
                            callback: function(value) {
                                return value + ' auto'; // Adjust formatting as needed
                            }
                        },
                        grid: {
                            color: '#ddd', // Set grid line color
                            borderDash: [12, 12] // Dashed grid lines
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Hide legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Lavori: ' + context.raw; // Customize tooltip text
                            }
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
        console.log('Order Counts:', orderCounts);
        console.log('Labels:', labels);
    });
</script>
