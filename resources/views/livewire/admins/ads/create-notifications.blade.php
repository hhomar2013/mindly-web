<div class="row p-3 border-start border-3 border-dark mb-3">
    <h3 class="border-bottom pb-2">{{ __('Notification Configuration') }}</h3>
    <div class="col-lg-6 mb-3">
        <label class="form-label fw-bold text-primary">{{ __('Notification Title') }}</label>
        <input type="text" class="form-control" placeholder="{{ __('Enter notification title') }}"
            wire:model.live="notification_title">
        @error('notification_title')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>

    <div class="col-lg-12 mb-3">
        <label class="form-label fw-bold text-primary">{{ __('Notification Text') }}</label>
        <textarea class="form-control" placeholder="{{ __('Enter notification text') }}" wire:model.live="notification_text"></textarea>
        @error('notification_text')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    </div>

</div>
