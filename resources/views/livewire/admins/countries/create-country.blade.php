      <div class="card-body">
          <form wire:submit.prevent="{{ $update ? 'updateCountry' :'store' }}">
              {{ csrf_field() }}
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="name">{{ __('Country Name AR') }}</label>
                          <input type="text" class="form-control" id="name" wire:model="name_ar" placeholder="{{ __('Enter country name') }}">
                          @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>

                      <div class="form-group">
                          <label for="name">{{ __('Country Name EN') }}</label>
                          <input type="text" class="form-control" id="name" wire:model="name_en" placeholder="{{ __('Enter country name') }}">
                          @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>

                      <div class="form-group">
                          <label for="name">{{ __('Country Code') }}</label>
                          <input type="text" class="form-control" id="name" wire:model="code" placeholder="{{ __('Enter country code') }}">
                          @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="image" class="btn">{{ __('Country Image') }}
                              <input type="file" name="" id="image" wire:model="image" hidden><br>
                              @if ($image)
                              <img src="{{ $this->getPreviewUrl($image) }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                              @elseif($old_image)
                              <img src="{{ asset('storage/' . $old_image) }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                              @else
                              <img src="{{ asset('assets/img/mindly_icon.png') }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                              @endif

                          </label>
                          @error('image') <span class="text-danger">{{ $message }}</span> @enderror
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
