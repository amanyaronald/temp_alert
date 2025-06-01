@props([
    'chartId',
    'series',
    'labels',
    'colors',
    'chartType',
    'height' => 320,
    'legend_show' => true,
    'legend_position' => 'bottom',
    'legend_horizontalAlign' => 'center',
    'legend_verticalAlign' => 'middle',
    'legend_floating' => false,
    'legend_fontSize' => '14px',
    'legend_offsetX' => '',
    'hasDataLabels' => false,
    'hasTooltip' => true,
])

<div id="{{ $chartId }}"></div>

@pushOnce('scripts')
    <script src="{{ URL::asset('/build/libs/apexcharts/apexcharts.min.js') }}"></script>
@endPushOnce

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // var colors = getChartColorsArray("donut_chart");
            var options = {
                chart: {
                    height: "{{ $height }}",
                    type: "{{ $chartType }}",
                    id: "{{ $chartId }}"
                },
                series: {!! json_encode($series) !!},
                labels: {!! json_encode($labels) !!},
                colors: {!! json_encode($colors) !!},
                legend: {
                    show: "{{ $legend_show }}",
                    position: "{{ $legend_position }}",
                    horizontalAlign: "{{ $legend_horizontalAlign }}",
                    verticalAlign: "{{ $legend_verticalAlign }}",
                    floating: "{{ $legend_floating }}",
                    fontSize: "{{ $legend_fontSize }}",
                    offsetX: "{{ $legend_offsetX }}",
                },
                tooltip: {
                    enabled: @json($hasTooltip),
                    y: {
                        formatter: function(val) {
                            return val;
                        }
                    }
                },
                dataLabels: {
                    enabled: @json($hasDataLabels),
                    formatter: function(val, opts) {
                        return opts.w.globals.labels[opts
                            .seriesIndex];
                    }
                },
                responsive: [{
                    breakpoint: 600,
                    options: {
                        chart: {
                            height: 240,
                        },
                        legend: {
                            show: false,
                        },
                    },
                }],
            };

            // Render chart
            var chart = new ApexCharts(document.querySelector("#{{ $chartId }}"), options);
            chart.render();
        });
    </script>
@endpush
