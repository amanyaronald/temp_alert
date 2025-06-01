@props([
    'checklist' => [],
])
<div>
    <div class="d-flex justify-content-center">
        <h5>Review Checklist</h5>
    </div>
    <div class="m-2 p-2 border">
        <div class="form-group col-md-12">
            <label for="checklist">Please review The checklist</label>
            @foreach ($checklist as $index => $item)
                <div class="form-check">
                    <input wire:model.live="checklist.{{ $index }}.status" class="form-check-input" type="checkbox"
                        id="check{{ $index }}">
                    <label class="form-check-label" for="check{{ $index }}">
                        {{ $item['name'] }}
                    </label>
                </div>
            @endforeach
            <hr class="my-2">
            @if ($errors->any())
                <div class="text-danger">
                    <ul class="mx-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
