{{-- <button type="button" class="btn btn-outline-primary waves-effect waves-light my-3 mx-2 " data-bs-toggle="modal"
    data-bs-target="#{{ $urlClass }}-add">Add</button> --}}
@props(['target' => 'add', 'size' => 'sm'])
<div>
    {{-- @can('' . 'add') --}}
    <button type="button" class="btn btn-outline-primary btn-sm waves-effect waves-light my-3 mx-2 "
        data-bs-toggle="modal" data-bs-target="#{{ $target }}">Add</button>
    {{-- @endcan --}}

</div>
