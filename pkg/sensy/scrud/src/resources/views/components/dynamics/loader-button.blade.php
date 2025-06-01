@props([
    'hasMessage' => true,
    'message' => 'Processing...',
    'hasOutline' => false,
    'color' => 'primary',
    'noText' => false,
    'size' => '',
    'id' => '',
])

<button id="{{ $id }}"
    class="btn {{ $size == 'sm' ? 'btn-sm' : '' }} btn-{{ $hasOutline ? 'outlined-' : '' }}{{ $color }}"
    type="button" disabled>
    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    @if (!$noText)
        @if ($hasMessage)
            {{ $message }}
        @endif
    @endif
</button>
