<div class="card-body">
    <form wire:submit.prevent="save">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">{{ __('Arabic Name') }}</label>
                    <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name"
                        wire:model="name_ar" placeholder="{{ __('Enter Arabic Name') }}">
                    @error('name_ar')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">{{ __('English Name') }}</label>
                    <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name"
                        wire:model="name_en" placeholder="{{ __('Enter English Name') }}">
                    @error('name_en')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">

                <div class="form-group">
                    <label for="image" class="btn">{{ __('Subject Image') }}
                        <input type="file" name="" id="image" wire:model="image" hidden><br>
                        @if ($image)
                            <img src="{{ $image->temporaryUrl() }}"
                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                        @elseif($image_old)
                            <img src="{{ asset('storage/' . $image_old) }}"
                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                        @else
                            <img src="{{ asset('assets/img/mindly_icon.png') }}"
                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                        @endif

                    </label>
                    @error('image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
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
