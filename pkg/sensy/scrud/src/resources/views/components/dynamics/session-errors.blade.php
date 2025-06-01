@foreach (has_errors() as $key =>$value)
<div class="alert alert-warning alert-dismissible fade show d-flex justify-content-between align-items-center"
role="alert">
<div>
    <strong>SERVICE ERROR:</strong> {{ session($value) }}

</div>
<div>
    <button type="button" class="close btn " data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
</div>
@endforeach
{{-- @if () --}}

{{-- @endif --}}
