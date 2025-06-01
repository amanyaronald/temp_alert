<div>
    {{-- Dynamic Add and Edit --}}
    {{-- @if (view()->exists('livewire.actions.modals.' . $urlClass . '.' . $urlClass . '-add'))
        @livewire('actions.modals.' . $urlClass . '.' . $urlClass . '-add', ['class' => $class, 'urlClass' => $urlClass])
    @endif --}}

    {{-- Dynamic Show --}}
    {{-- @if (view()->exists('components.' . $urlClass . '.' . $urlClass . '-show'))
        @livewire('actions.modals.general.show', ['class' => $class, 'urlClass' => $urlClass])
    @endif --}}

    {{-- Dynamic Delete --}}
    {{-- @livewire('actions.modals.general.delete', ['class' => $class, 'urlClass' => $urlClass]) --}}
</div>
