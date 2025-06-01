@props(['records', 'type' => 'show', 'attrs' => [], 'col' => '12', 'title', 'attr'])


{{-- @dd($records, $type, $attrs, $col, $title, $attr) --}}
@if ($type == 'show')
    <div class="accordion col-md-{{ $col }}" id="document-list">
        @foreach ($records as $rec)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $rec->id }} pb-0 mb-0">
                    <button class="d-flex justify-content-between accordion-button collapsed py-2 my-0" type="button"
                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $rec->id }}" aria-expanded="false"
                        aria-controls="collapse{{ $rec->id }}">
                        <div>
                            {{ data_get($rec, $attr) }}
                        </div>
                    </button>
                </h2>
                <div id="collapse{{ $rec->id }}" class="accordion-collapse collapse"
                    aria-labelledby="heading{{ $rec->id }}" data-bs-parent="#document-list">
                    <div class="accordion-body row">
                        @foreach ($attrs[0] as $k => $a)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="font-weight-bold">{{ $a }}</label>
                                    <p>{{ data_get($rec, $attrs[1][$k]) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="accordion col-md-11" id="document-list">
        {{-- @foreach ($documents as $doc) --}}
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{ 'A' }} pb-0 mb-0">
                <button class="d-flex justify-content-between accordion-button collapsed py-2 my-0" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapse{{ 'A' }}" aria-expanded="false"
                    aria-controls="collapse{{ 'A' }}">
                    <div>
                        NAME
                    </div>
                </button>
            </h2>
            <a class="h6 px-4 my-0 py-0 text-primary" click.prevent href="" target="_blank"><i
                    class="bx bx-fullscreen align-self-center"></i> Full
                View </a>
            <div id="collapse{{ 'A' }}" class="accordion-collapse collapse"
                aria-labelledby="heading{{ 'A' }}" data-bs-parent="#document-list">
                <div class="accordion-body">
                    BODDY
                </div>
            </div>
        </div>
    </div>
@endif
