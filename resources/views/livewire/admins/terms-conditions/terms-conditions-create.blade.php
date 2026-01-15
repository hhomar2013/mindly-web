  <form wire:submit.prevent="save">
      <div class="mb-4">
          <div class="row">
              <div class="col-lg-6">
                  <label for="">{{ __('Type') }}</label>
                  <select class="form-control mb-3" wire:model.live="type">
                      <option value="terms">{{ __('Terms and Conditions') }}</option>
                      <option value="privacy">{{ __('Privacy Policy') }}</option>
                  </select>
              </div>
              <div class="col-lg-6">
                  <label for="">{{ __('Version number') }}</label>
                  <input type="text" class="form-control mb-3" wire:model="termVersion" />
              </div>
          </div>

          <label class="form-label">شروط وأحكام المنصة</label>
          <div wire:ignore>
              <div id="editor">
                  {!! $content !!}
              </div>
          </div>
          @error('content')
          <span class="text-danger text-sm">{{ $message }}</span>
          @enderror
      </div>

      <div class="d-flex justify-content-start">
          <button type="submit" class="btn btn-primary">
              <i class="fa-solid fa-save me-1"></i> {{ __('save') }}
          </button>
      </div>
  </form>
