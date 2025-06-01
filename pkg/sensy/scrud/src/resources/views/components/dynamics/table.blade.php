@props([
    'list' => [],
    'type' => 'list',
    'align' => 'middle',
    'onclick' => 'triggerAllModals',
    'selectAllId' => 'selectAllCheck',
    'paginate' => true,
    'scrollable' => false,
    'selected' => null,
])

{{-- <div class="row scrollable-container" style="max-height: 20px; overflow: auto;"> --}}
@if (!empty($selected))
    <div class="d-flex justify-content-between">
        <div>
            <span class="fs-6 text-primary" style="font-size: 1pt">Selected: {{ count($selected) }}</span> |
            <a wire:click="clearSelection" class="fs-6 text-danger text-decoration-underline cursor-pointer"
               style="font-size: 1pt">Clear All
                Selections</a>
        </div>
        {{-- <span class="fs-6" style="font-size: 1pt">Total 300</span> --}}
    </div>
@endif
<table class="table table-sm align-{{ $align }} mb-0">
    <thead class="table-light">
    {{ $thead }}
    </thead>
    <tbody class="font-size-12">
    {{ $tbody }}
    </tbody>
</table>
@if ($paginate)
    <x-scrud::dynamics.lists.list-footer :items="$list"/>
@endif
{{-- </div> --}}
