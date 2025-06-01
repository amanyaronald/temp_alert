@props([
    'status',
    'color' => 'primary',
    'size' => 'md',
    'rounded' => 'md',
    'shadow' => 'sm',
    'pill' => false,
    'icon' => false,
    'iconClass' => 'bx bx-check',
    'iconSize' => 'sm',
    'iconPosition' => 'left',
    'hasIcon' => false,
    'message' => null,
])
@php
    if (in_array(strtolower($status), ['active', 'success', 'completed'])) {
        $color = 'success';
    } elseif (in_array(strtolower($status), ['inactive', 'danger'])) {
        $color = 'danger';
    } elseif (in_array(strtolower($status), ['pending', 'warning'])) {
        $color = 'warning';
    } elseif (in_array(strtolower($status), ['failed', 'danger', 'critical'])) {
        $color = 'danger';
    } elseif (in_array(strtolower($status), ['processing', 'info', 'saved'])) {
        $color = 'info';
    } else {
        $color = 'primary';
    }

    if ($status == 'Inactive') {
        $color = 'danger';
    }
@endphp

<span
    class="badge bg-{{ $color }} {{ $size }} {{ $rounded }} {{ $shadow }} p-1 {{ $pill ? 'rounded-pill' : '' }}">
    @if ($hasIcon && $iconPosition == 'left')
        <i class="{{ $iconClass }} {{ $iconSize }}"></i>
    @endif
    {{ $message ?? $status }}
    @if ($hasIcon && $iconPosition == 'right')
        <i class="{{ $iconClass }} {{ $iconSize }}"></i>
    @endif
</span>
