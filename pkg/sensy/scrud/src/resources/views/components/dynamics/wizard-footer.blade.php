@props([
    'step' => 1,
    'total_steps',
])
<div>
    <div class="d-flex justify-content-end">
        @if ($step > 1)
            <button class="btn btn-outline-warning mr-2" wire:click='previousStep'>Previous</button>
        @endif
        @if ($step < $total_steps)
            <button class="btn btn-primary mx-4" wire:click='nextStep'>Next</button>
        @endif
        @if ($step == $total_steps)
            <button class="btn btn-outline-primary mx-2" wire:click='submit'>Submit</button>
        @endif
    </div>

</div>
