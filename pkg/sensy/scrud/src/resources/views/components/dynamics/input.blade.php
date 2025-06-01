@props(['attributeName', 'modelBind', 'inputType', 'label', 'isRequired'])

<div>
    <div class="form-group">
        <label @if ($inputType != 'checkbox') for="{{ $attributeName }}" @endif>{{ $label }}</label>
        @if ($inputType == 'checkbox')
            <div class="form-check">
        @endif
        <input type="{{ $inputType }}" class="{{ $inputType == 'checkbox' ? 'form-check-input' : 'form-control' }}"
            placeholder="Enter {{ $label }}" @if ($isRequired) required @endif
            wire:model.live="{{ $modelBind }}">
        @if ($inputType == 'checkbox')
            <label for="{{ $attributeName }}" class="form-check-label">Yes</label>
    </div>
    @endif
</div>
@error($modelBind)
    <p class="invalid-feedback">
        {{ $message }}
    </p>
@enderror
</div>
