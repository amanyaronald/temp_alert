@props(['section' => null, 'service' => null, 'hasProgress' => true, 'hasTrashed' => true])

@if ($hasProgress)
    <li wire:click="dispatch('inProgressFilter',{caller:'progress'})" class="nav-item" role="presentation">
        <a class="nav-link {{ $this->section == 2 ? 'active' : '' }} " data-bs-toggle="tab" href="#home" role="tab"
            aria-selected="true">
            <span class="d-block d-sm-none"><i class="bx bx-history"></i></span>
            <span class="d-none d-sm-block d-sm-flex gap-1">
                <i class="bx bx-history text-lg" style="font-size: 1.2rem;"></i> <span>
                    {{ is_null($service) ? 'In' : $service }} Workflow
                </span>
            </span>
        </a>
    </li>
@endif

@if ($hasTrashed)
    <li wire:click="dispatch('inProgressFilter',{caller:'deleted'})" class="nav-item" role="presentation">
        <a class="nav-link {{ $this->section == 3 ? 'active' : '' }}"" data-bs-toggle="tab" href="#profile"
            role="tab" aria-selected="false" tabindex="-1">
            <span class="d-block d-sm-none"><i class="bx bx-trash"></i></span>
            <span class="d-none d-sm-block d-sm-flex gap-1">
                <i class="bx bx-trash text-danger text-lg" style="font-size: 1.2rem;"></i> <span>
                    Trashed {{ camel_to_sentence($service) }}
                </span>
            </span>
        </a>
    </li>
@endif
