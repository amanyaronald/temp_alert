@props(['field', 'col' => 4, 'disabled' => false])
@php
    # Check if number is updated after verification and reset cerification
    $verification_sent = $field . '_verification_sent';
    $verified = $field . '_verified';
    $verification = $field . '_verification';
    $verification_code = $field . '_verification_code';

@endphp
<div class="col-md-{{ $col }} col-sm-12">
    <div class="form-group">
        <label for="{{ $field }}">{{ camel_to_sentence($field) }}</label>
        <div class="input-group">
            <input type="text" @disabled($disabled)
                class="form-control @error($field) is-invalid @enderror @if ($this->$verified) is-valid @endif"
                wire:model.live="{{ $field }}" placeholder="Enter {{ camel_to_sentence($field) }}" required>

            @if (!$this->$verified)
                @if ($this->$verification_sent)
                    <input type="text" @disabled($disabled)
                        class="form-control @error($verification) is-invalid @enderror"
                        wire:model.live="{{ $verification }}" placeholder="Enter OTP" required>
                @endif

                <div class="input-group-append">
                    <button @disabled($disabled) class="btn btn-outline-primary" type="button"
                        @if (!$this->$verification_sent) wire:click="sendVerificationCode('{{ $field }}')" @else wire:click="confirmCode('{{ $field }}')" @endif>
                        <i class="bx bx-check"
                            @if (!$this->$verification_sent) wire:target="sendVerificationCode('{{ $field }}')"  @else wire:target="confirmCode('{{ $field }}')" @endif
                            wire:loading.class.remove="bx bx-check"
                            wire:loading.class="spinner-border spinner-border-sm"></i>
                        @if (!$this->$verification_sent)
                            Verify
                        @else
                            Confirm
                        @endif
                    </button>
                </div>
            @endif
        </div>
        @error($field)
            <p class="custom-error-message">
                {{ $message }}
            </p>
        @enderror
        @error($verification)
            <p class="custom-error-message">
                {{ $message }}
            </p>
        @enderror
        @error($verified)
            <p class="custom-error-message">
                {{ $message }}
            </p>
        @enderror
    </div>
</div>
