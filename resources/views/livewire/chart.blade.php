@push('sub-title')
    @if ($title)
        {{ $title }} |
    @endif
@endpush
<div class="relative flex flex-col-reverse">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div id="chartContainer" class="block">
        <h2 class="text-xl font-bold m-4 text-center">{{ $title }}</h2>
        <div wire:ignore id="myChart"></div>
    </div>
    <hr class="border-gray-200 dark:border-gray-600 my-2">
    <div class="h-full w-full p-4 flex-1">
        {{ $this->form }}
    </div>
    <div class="lds-spinner opacity-0 transition-opacity duration-500" wire:loading.class="opacity-100">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    <script wire:ignore type="text/javascript">
        google.charts.load('current', {
            'packages': ['corechart']
        });

        Livewire.on('chartDataUpdated', (input) => {
            console.log('Updating chart data');
            const dataset = input.dataset;
            if (!dataset) {
                return;
            }
            const row_headers = input.rows;
            const column_headers = input.columns;
            const data = new google.visualization.DataTable();
            console.log(input)
            data.addColumn('string', 'Year/Round');
            for (const column_header of column_headers) {
                data.addColumn('number', column_header);
            }
            let rows = [];
            for (let i = 0; i < row_headers.length; i++) {
                let row = [row_headers[i]];
                for (let j = 0; j < column_headers.length; j++) {
                    row.push(dataset[i] ? dataset[i][j] : undefined);
                }
                rows.push(row);
            }
            console.log(rows);
            data.addRows(rows);

            var options = {
                curveType: 'function',
                legend: {
                    position: 'top',
                    maxLines: 10,
                    textStyle: {
                        fontSize: 12
                    }
                },
                explorer: {
                    axis: 'vertical',
                    keepInBounds: true
                },
                chartArea: {
                    height: '100%',
                    width: '100%',
                    top: 80,
                    left: 80,
                    right: 16,
                    bottom: 80
                },
                height: '100%',
                width: '100%',
            };

            const chart = new google.visualization.LineChart(document.getElementById('myChart'));
            chart.draw(data, options);

            if (data.title) {
                document.title = data.title + ' | Trends | JoSAA Analysis';
            } else {
                document.title = 'Trends | JoSAA Analysis';
            }
        });

        window.addEventListener('load', () => {
            Livewire.emit('updateChartData');
        });
    </script>

</div>
