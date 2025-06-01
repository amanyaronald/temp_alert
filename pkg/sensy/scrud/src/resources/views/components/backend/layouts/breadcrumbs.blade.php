@props([
    'driver' => 'route', # route | link
    'hasLink' => false,
    'link' => '#',
    'heading',
    'main',
    'sub',
])
<div class="pagetitle">
    <h1>{{ $heading }}</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a
                    href="@if ($hasLink) {{ $link }} @endif
            ">{{ $main }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $sub }}</li>
        </ol>
    </nav>
</div>
