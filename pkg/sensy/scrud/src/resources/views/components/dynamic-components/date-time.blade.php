@php
    $formattedDateTime = \Carbon\Carbon::parse($timestamp)->format('H:i, jS F Y');
@endphp
{{ $formattedDateTime }}
