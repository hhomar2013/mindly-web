<div class="card-body">
    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">{{ __('Arabic Name') }}</label>
                    <input type="text" class="form-control  @error('name_ar') is-invalid @enderror" id="name"
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
                    <label for="type">{{ __('Duration in months') }}</label>
                    <input type="number" name="" id=""
                        class="form-control @error('duration') is-invalid @enderror" min="1"
                        wire:model="duration" />
                    @error('duration')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>


            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="color">{{ __('Color') }}</label>
                    <select name="color" id="" class="form-control @error('color') is-invalid @enderror"
                        wire:model="color">
                       <option value="">{{ __('Select Color') }}</option>
                       <option class="text-white bg-primary" value="text-white bg-primary">
                     {{ __('Blue') }}
                    </option>
                       <option class="text-white bg-success" value="text-white bg-success">{{ __('Green') }}</option> 
                    </select>
                    @error('color')
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

        <button wire:click="back" class="btn btn-warning btbn-rounded mt-3" wire:click.prevent="back()">
            <i class="fa fa-arrow-left"></i>
        </button>

    </form>
</div>
