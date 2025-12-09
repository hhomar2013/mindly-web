<div>
    <style>
        .form-switch {
            display: inline-flex !important;
            align-items: center !important;
        }

    </style>
    <div class="form-check form-switch text-center" dir="ltr">
        <input class="form-check-input" type="checkbox" role="switch" id="toggle-{{ $model->id }}" wire:model.live="isPublished" name="toggle-{{ $model->id }}">
        <label class="form-check-label" id="toggle-{{ $model->id }}" for="toggle-{{ $model->id }}"></label>
    </div>
</div>
