@props([
    'width' => '20px',
    'align' => 'middle',
    'emit' => 'selectedItem',
    'id',
])
<td class="py-1">
    <div class="form-check ">
        <input class="form-check-input" type="checkbox" id="menuCheck{{ $id }}"
            wire:click="$dispatch('selectedItem', { itemId: '{{ $id }}' })">
        <label class="form-check-label" for="menuCheck{{ $id }}"></label>
    </div>
</td>
