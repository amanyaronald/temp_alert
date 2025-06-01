@props([
    'id' => 'world-map-markers',
    'width' => '600px',
    'height' => '350px',
    'map' => 'world_mill_en',
    'markers' => '[]',
])


<div>

    <div id="{{ $id }}" style="width: {{ $width }}; height:{{ $height }}"></div>

    @pushOnce('scripts')
        <script src="{{ URL::asset('/build/libs/jquery-vectormap/jquery-vectormap.min.js') }}"></script>
    @endPushOnce

    @push('scripts')
        <script>
            !(function($) {
                "use strict";
                var VectorMap = function() {};
                (VectorMap.prototype.init = function() {
                    //various examples
                    $("#{{ $id }}").vectorMap({
                        map: "{{ $map }}",
                        normalizeFunction: "polynomial",
                        hoverOpacity: 0.7,
                        hoverColor: true,
                        regionStyle: {
                            initial: {
                                fill: "#d4dadd",
                            },
                        },
                        markerLabelStyle: {
                            initial: {
                                fontFamily: "'Inter', sans-serif",
                                fontSize: 13,
                                fontWeight: 500,
                                fill: '#35373e',
                            },
                            hover: {
                                fill: 'red'
                            },
                            selected: {
                                fill: 'blue'
                            }
                        },
                        markerStyle: {
                            initial: {
                                r: 9,
                                fill: "#556ee6",
                                "fill-opacity": 0.9,
                                stroke: "#fff",
                                "stroke-width": 7,
                                "stroke-opacity": 0.4,
                            },

                            hover: {
                                stroke: "#fff",
                                "fill-opacity": 1,
                                "stroke-width": 1.5,
                            },
                        },
                        backgroundColor: "transparent",
                        markers: @json($markers),
                    });
                }),
                ($.VectorMap = new VectorMap()),
                ($.VectorMap.Constructor = VectorMap);
            })(window.jQuery),
            //initializing
            (function($) {
                "use strict";
                $.VectorMap.init();
            })(window.jQuery);
        </script>
    @endpush
</div>
