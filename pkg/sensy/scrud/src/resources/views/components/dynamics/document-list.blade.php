@props(['documents', 'name' => 'name', 'document_path' => 'document_path'])
<div class="row">
    <div class="accordion col-md-11" id="document-list">
        @php
            $score = 0;
        @endphp
        {{-- @dd($documents) --}}
        @foreach ($documents as $doc)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $doc->id }} pb-0 mb-0">
                    <button class="d-flex justify-content-between accordion-button collapsed py-2 my-0" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $doc->id }}" aria-expanded="false"
                            aria-controls="collapse{{ $doc->id }}">
                        <div class="">
                            <i
                                class="bx bx-{{ $doc->app_status == 1 ? 'check' : 'x' }}-circle text-{{ $doc->app_status == 1 ? 'primary' : '' }}"></i>
                            {{ $doc->name }}
                            <x-scrud::dynamics.tooltips :message="$doc->description"/>
                            | <span
                                class="text-primary">{{ $doc->documentType->name }}</span>
                        </div>
                    </button>
                </h2>
                <a class="h6 px-4 my-0 py-0 text-primary" click.prevent href="{{ asset($doc->document_path) }}"
                   target="_blank"><i class="bx bx-fullscreen align-self-center"></i> Full
                    View </a>
                <div id="collapse{{ $doc->id }}" class="accordion-collapse collapse"
                     aria-labelledby="heading{{ $doc->id }}" data-bs-parent="#document-list">
                    <div class="accordion-body">
                        <iframe src="{{ asset($doc->document_path) }}" width="100%" height="400">
                            This browser does not support PDFs. Please download the Document to view it:
                            <a href="{{ asset($doc->document_path) }}">Download
                                PDF</a></iframe>
                    </div>
                </div>
            </div>
            @php
                $score += $doc->documentType->score;
            @endphp
        @endforeach
    </div>
    <div class="col-md-1">
        <style>
            .circle {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                border: 4px solid #235e14;
                /* Set border color */
                display: flex;
                justify-content: center;
                align-items: center;

                /* Set text color */
                font-size: 25px;
            }
        </style>
        <div class='d-flex justify-content-center  align-items-center flex-column'>
            <div class="circle text-primary">{{ $score }} </div>
            <div class="text-primary h4">SCORE</div>
        </div>
    </div>
</div>
