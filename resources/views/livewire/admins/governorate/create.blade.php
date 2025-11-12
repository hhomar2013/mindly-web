      <div class="card-body">
          <form wire:submit.prevent="store">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="name">{{ __('Governorate Name') }}</label>
                          <input type="text" class="form-control" id="name" wire:model="name" placeholder="{{ __('Enter governorate name') }}">
                          @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>

                      <div class="form-group">
                          <label for="name">{{ __('Countries') }}</label>
                          <select name="" id="" class="form-control" wire:model="country_id">
                              <option value="">{{ __('Select Country') }}</option>
                              @foreach ($countries as $country)
                              <option value="{{ $country->id }}">{{ $country->name }}</option>
                              @endforeach
                          </select>
                          @error('country_id') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>
                  </div>
              </div>
              <button type="submit" class="btn btn-primary mt-3">
                  <i class="fa fa-save"></i>
                  {{ __('save') }}
              </button>
              &nbsp;&nbsp;
              <button wire:click.prevent="switchAction('table')" class="btn btn-warning btbn-rounded mt-3">
                  {{ __('Back') }}
                  <i class="fa fa-arrow-left"></i>
              </button>

          </form>
      </div>
