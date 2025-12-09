<div class="card">
    <div class="card-body">
        <form action="" wire:submit.prevent="saveAds">
            <div class="row">
                <div class="col-10">
                    <div class="col-lg-6 mb-3">
                        <label for="course_name">{{ __('Ads Type') }}</label>
                        <select id="course_name" class="form-control" wire:model.live="type">
                            <option value="">{{ __('Select Ads Type') }}</option>
                            @foreach ($types as $type)
                                <option value="{{ $type }}">
                                    {{ $type == 'sliders' ? __('Sliders') : __('Popup') }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label for="optional_link">{{ __('Ads Comment') }}</label>
                        <input type="text" id="optional_link" class="form-control" wire:model="comment">
                    </div>
                </div>
                <div class="col-2">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="image" class="btn">{{ __('Ads Image') }}
                                <input type="file" name="" id="image" wire:model="image" hidden><br>
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}"
                                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                @elseif($old_image)
                                    <img src="{{ asset('storage/' . $old_image) }}"
                                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                @else
                                    <img src="{{ asset('assets/img/mindly_icon.png') }}"
                                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                @endif
                            </label>
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>{{-- image & wire:model="image" --}}
                    </div>
                </div>

            </div> {{-- End row --}}

            <div class="text-start">
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                    {{ __('Save Ads') }}
                </button>
                <button class="btn btn-danger" wire:click.prevent="index()">
                    <i class="fa fa-arrow-left"></i>
                </button>
            </div>
        </form>
    </div>
</div>
