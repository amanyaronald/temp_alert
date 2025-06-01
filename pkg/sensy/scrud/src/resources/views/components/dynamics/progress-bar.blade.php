@props(['value' => 0, 'total' => 100])

@php
    $percentage = ($total > 0) ? ($value / $total) * 100 : 0;

    $color = 'bg-danger';
    if ($percentage > 50) {
        $color = 'bg-warning';
    }
    if ($percentage > 75) {
        $color = 'bg-cyan';
    }
    if ($percentage == 100) {
        $color = 'bg-success';
    }
@endphp

<div class="progress" style="height: 5px;">
    <div class="progress-bar {{ $color }}" role="progressbar"
         style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}"
         aria-valuemin="0" aria-valuemax="100">
    </div>
</div>
