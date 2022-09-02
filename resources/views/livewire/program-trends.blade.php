<div>
    <div class="h-full w-full p-2">
        {{ $this->form }}
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"
        integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <canvas id="myChart" width="40" height="40"></canvas>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const plugin = {
            id: 'emptyChart',
            afterDraw(chart, args, options) {
                const {
                    datasets
                } = chart.data;
                let hasData = false;

                for (let dataset of datasets) {
                    if (dataset.data.length > 0 && dataset.data.some(item => item !== 0)) {
                        hasData = true;
                        break;
                    }
                }

                if (!hasData) {
                    const {
                        chartArea: {
                            left,
                            top,
                            right,
                            bottom
                        },
                        ctx
                    } = chart;
                    const centerX = (left + right) / 2;
                    const centerY = (top + bottom) / 2;

                    chart.clear();
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('No data to display', centerX, centerY);
                    ctx.restore();
                }
            }
        };
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {},
            plugins: [plugin],
            options: {
                scales: {
                    y: {
                        reverse: true,
                    },
                }
            }
        });
        Livewire.on('chartDataUpdated', (data) => {
            myChart.data = data;
            myChart.update();
        });
    </script>

</div>
