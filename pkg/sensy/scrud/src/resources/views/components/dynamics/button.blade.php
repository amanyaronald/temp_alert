@props([
    'hasLoader' => false,
    'customProcessmessage' => null, //Custom Processing Message
    'color' => 'primary',
    'class' => 'mx-4',
    'hasCustom' => false,
    'hasIcon' => false,
    'icon',
    'icon_size' => null,
    'hasSubmit' => true,
    'action' => 'submit',
    'text',
    'isOutlined' => false,
    'size' => false,
    'isDisabled' => false,
    'isAppended' => true,
    'append' => null,
    'key' => null,
    'noText' => false,
    'size' => '',
])

<div class="{{ $isAppended || isset($append) ? 'btn-group' : '' }}">

    @if ($hasCustom)
        {{ $slot }}
    @else
        @if ($hasSubmit)
            <button @if ($hasLoader) wire:loading.remove @endif @disabled($isDisabled)
            id='{{ $action }}' wire:loading.attr="disabled" wire:target="{{ $action }}"
                    @if (!is_null($key)) wire:key="{{ $key }}" @endif
                    class="btn {{ $size == 'sm' ? 'btn-sm' : '' }} btn-{{ $isOutlined ? 'outline-' : '' }}{{ $color }} {{ $isAppended || isset($append) ? '' : $class }}"
                    wire:click="{{ $action }}">
                @if ($hasIcon)
                    <i class="{{ $icon }} {{ $icon_size }} m-0"></i>
                @endif
                @if (!$noText)
                    {{ $text }}
                @endif
            </button>
        @endif
    @endif

    @if ($hasLoader)
        <div wire:loading wire:target="{{ $action }}">
            @if ($customProcessmessage != null)
                <x-scrud::dynamics.loader-button :id="$action" :size="$size" :message="$customProcessmessage"/>
            @else
                <x-scrud::dynamics.loader-button :id="$action" :size="$size"/>
            @endif
        </div>
    @endif

    @if (isset($append))
        {{ $append }}
    @endif
</div>
