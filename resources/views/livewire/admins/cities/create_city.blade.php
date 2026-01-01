      <div class="card-body">
          <form wire:submit.prevent="save">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="name">{{ __('Arabic Name') }}</label>
                          <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name" wire:model="name_ar" placeholder="">
                          @error('name_ar') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>

                      <div class="form-group">
                          <label for="name">{{ __('English Name') }}</label>
                          <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name" wire:model="name_en" placeholder="">
                          @error('name_en') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>
                  </div>

              </div>
              <button type="submit" class="btn btn-primary mt-3">
                  <i class="fa fa-save"></i>
                  {{ __('save') }}
              </button>
              &nbsp;&nbsp;
              <button wire:click.prevent="back" class="btn btn-warning btbn-rounded mt-3">
                  {{ __('Back') }}
                  <i class="fa fa-arrow-left"></i>
              </button>

          </form>
      </div>
