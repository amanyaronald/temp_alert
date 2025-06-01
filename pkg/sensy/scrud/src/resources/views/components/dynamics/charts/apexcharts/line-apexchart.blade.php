@props([
    'chartId',
    'title' => 'Default title',
    'xaxisTitle' => 'xaxis Default title',
    'yaxisTitle' => 'yaxis Default title',
    'series' => [],
    'categories' => [],
    'height' => 380,
    'showLegend' => true,
    'legendPosition' => 'top',
    'legendHorizontalAlign' => 'right',
    'legendFloating' => true,
])

<div id="{{ $chartId }}"></div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                chart: {
                    height: {{ $height }},
                    type: 'line',
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: false
                    }
                },
                colors: getChartColorsArray("trans_history"),
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    width: [3],
                    curve: 'straight'
                },
                series: {!! json_encode($series) !!},
                title: {
                    text: 'Weekly Transactions',
                    align: 'left',
                    style: {
                        fontWeight: '500',
                    },
                },
                grid: {
                    row: {
                        colors: ['transparent', 'transparent'],
                        opacity: 0.2
                    },
                    borderColor: '#f1f1f1'
                },
                markers: {
                    style: 'inverted',
                    size: 6
                },
                xaxis: {
                    categories: {!! json_encode($categories) !!},
                    title: {
                        text: "{{ $xaxisTitle }}"
                    }
                },
                yaxis: {
                    title: {
                        text: "{{ $yaxisTitle }}"
                    },
                    min: 5,
                    max: 40
                },
                legend: {
                    position: '{{ $legendPosition }}',
                    horizontalAlign: '{{ $legendHorizontalAlign }}',
                    floating: {{ $legendFloating }},
                    offsetY: -25,
                    offsetX: -5,
                    show: {{ $showLegend }}
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        chart: {
                            toolbar: {
                                show: false
                            }
                        },
                        legend: {
                            show: false
                        },
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#{{ $chartId }}"), options);
            chart.render();
        });
    </script>
@endpush
