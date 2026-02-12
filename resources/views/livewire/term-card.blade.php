<div class="card h-100 shadow-sm border-0 ">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <span class="badge {{ $term->is_active ? 'bg-success' : 'bg-light text-dark' }}">
                {{ $term->is_active ? __('Active') : __('Archived') }}
            </span>
            <small class="text-muted">{{ $term->created_at->format('Y-m-d') }}</small>
        </div>

        <h6 class="card-title fw-bold">إصدار: {{ $term->version }}</h6>

        <p class="card-text text-muted small mt-2">
            {{ Str::limit(strip_tags($term->content), 100) }}
        </p>
    </div>

    <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between">
        <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $term->id }})">
            <i class="fa-solid fa-pen-to-square"></i> تعديل
        </button>
        @if(!$term->is_active)
        <button class="btn btn-sm btn-outline-success" wire:click="activate({{ $term->id }})">
            تفعيل
        </button>
        @endif
    </div>
</div>
