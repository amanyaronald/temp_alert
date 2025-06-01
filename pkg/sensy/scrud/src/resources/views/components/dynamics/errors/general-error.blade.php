<div class="d-flex justify-content-end pt-2 px-3">
    @error('general_error')
    <span class="text-danger" role="alert">
                    {{ $message }}
                </span>
    @enderror
</div>

