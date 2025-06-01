@props([
    'model',
    'driver' => 'livewire', #livewire | #session
])
@if ($driver == 'livewire')
    @error($model)
        <span class="text-danger" role="alert">
            {{ $message }}
        </span>
    @enderror
@else
    @if ($errors->has($model))
        <span class="text-danger" role="alert">
            @foreach ($errors->get($model) as $error)
                {{ $error }}
            @endforeach
        </span>
    @endif
@endif
