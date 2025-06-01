@props(['component', 'hasAdd', 'hasShow', 'hasDelete', 'service'])
<div>
    @if ($hasAdd || $hasEdit)
        @livewire($component . '-add', ['target' => 'add', 'service' => $service])
    @endif

    @if ($hasShow)
        @livewire($component . '-show', ['target' => 'show', 'service' => $service])
    @endif

    @if ($hasDelete)
        @livewire($component . '-delete', ['target' => 'delete', 'service' => $service])
    @endif
</div>
