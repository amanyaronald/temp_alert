@props(['compile', 'edit', 'reset'])
<div class="d-flex justify-content-center col-sm-12 my-4">
    <div class="btn-group" role="group">
        <x-scrud::dynamics.button action="{{ $compile }}" text="{{ $edit !== null ? 'Update' : 'Stage' }}"
                                  :hasIcon="true"
                                  icon="{{ $edit !== null ? 'bx bx-check' : 'bx bx-plus' }}" :hasLoader="true"
                                  customProcessmessage="Staging..."
                                  :isOutlined="true"/>
        <button type="button" class="btn btn-outline-warning" wire:click='{{ $reset }}'><i
                class="bx bx-x"></i>Reset
        </button>
    </div>
</div>
