@props([
    'wizardNav' => false,
    'id' => 'modal',
    'size' => 'sm',
    'heading' => 'Default Heading',
    'has_loader' => false,
    'has_error' => true,
    'footer' => false,
    'backdrop_static' => false,
    'v_centered' => false,
    'driver'=>'livewire', # livewire/session
    'animation'=>'fade', #zoom-in, fade
])
<div>
    @if($driver === 'livewire')
        @teleport('modals')
    @endif
    <div id="{{ $id }}" @if ($backdrop_static) data-bs-backdrop='static' @endif wire:ignore.self
         class="modal {{$animation}}" style="z-index: 2000" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog @if($v_centered) modal-dialog-centered @endif  modal-{{ $size }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">{{ $heading }}</h5>
                    <button type="button" wire:click.prevent="_resetComponent" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"> <i class="fe fe-x-square "></i></button>
                </div>
                @if ($has_loader)
                    <x-scrud::dynamics.progress-loader/>
                @endif

                {{ $wizardNav }}
                <div class="modal-body">
                    {{ $slot }}
                </div>
                @error('general_error')
                <span class="invalid-feedback" role="alert">
                            {{ $message }}
                        </span>
                @enderror
                @if ($footer)
                    <div class="modal-footer">
                        {{ $footer }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @if($driver === 'livewire')
        @endteleport
    @endif
</div>
