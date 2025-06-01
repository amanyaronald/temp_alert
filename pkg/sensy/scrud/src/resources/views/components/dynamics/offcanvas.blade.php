@props([
    'wizardNav' => false,
    'id' => 'offcanvas',
    'size' => 'sm',
    'heading' => 'Default Heading',
    'has_loader' => false,
    'has_error' => true,
    'footer' => false,
    'backdrop_static' => false,
'position' => 'end',
    'driver' => 'livewire' # livewire/session
])

@if($driver === 'livewire')
    @teleport('modals')
@endif

<div id="{{ $id }}"
     class="offcanvas offcanvas-{{$position}}"
     aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">{{ $heading }}</h5>
        <button type="button" wire:click.prevent="_resetComponent" class="btn-close"
                data-bs-dismiss="offcanvas" aria-label="Close"> <i class="fe fe-x-square "></i></button>
    </div>

    @if ($has_loader)
        <x-scrud::dynamics.progress-loader/>
    @endif


    {{ $wizardNav }}

    <div class="offcanvas-body">
        {{ $slot }}
    </div>

    @error('general_error')
    <span class="invalid-feedback" role="alert">
            {{ $message }}
        </span>
    @enderror

    @if ($footer)
        <div class="offcanvas-footer">
            {{ $footer }}
        </div>
    @endif
</div>

@if($driver === 'livewire')
    @endteleport
@endif
