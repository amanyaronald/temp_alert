@props(['list', 'selectedAll' => false])
<div class="form-check font-size-16 align-middle">
    <input wire:model.live="selectedAll" wire:click="selectAllToggle({{ json_encode($list) }})" class="form-check-input"
        type="checkbox" id="transactionCheck01" @checked($selectedAll)>
    <label class="form-check-label" for="transactionCheck01"></label>
</div>
