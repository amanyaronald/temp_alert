@props(['message', 'placement' => 'top', 'color' => ''])
<span class="btn p-0" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $message }}">
    <i class="bx bx-info-circle text-{{ $color }}"></i>
</span>
{{-- <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="popover" data-bs-title="Popover title"
    data-bs-content="And here's some amazing content. It's very engaging. Right?">Popover Test</button> --}}
