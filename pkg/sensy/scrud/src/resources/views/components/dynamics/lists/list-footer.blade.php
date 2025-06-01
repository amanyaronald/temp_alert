@props(['items', 'scrollable' => false])

<div>
    <div class="d-flex justify-content-center">
        {{-- <x-scrud::dynamics.progress-loader /> --}}
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $items->links(data: ['scrollTo' => $scrollable]) }}
    </div>
</div>
