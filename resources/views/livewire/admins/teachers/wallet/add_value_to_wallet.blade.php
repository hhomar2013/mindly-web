<div>
    <div class="row p-4">
        <div class="car-header justify-content-between align-items-center">
            <div class="card-title">
                <h4>
                    <i class="fa-solid fa-graduation-cap"></i>
                    {{ $teacher->name }}
                </h4>
                <h5 class="">
                    {{ __('Balance') }} &nbsp;<b
                        class="{{ $teacher->wallet->balance <= 0 ? 'text-danger' : 'text-success' }}">{{ $teacher->wallet->balance }}</b>
                    &nbsp; {{ __('EGP') }}
                </h5>
            </div>
            <div class="text-end">
                <button class="btn btn-danger btn-sm" wire:click.prevent="back()">
                    {{ __('Back') }}
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
            </div>
        </div>
        <form wire:submit.prevent="save">
            <div class="card-body">

                {{-- Row Right Items --}}
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="name">{{ __('Amount') }}</label>
                            <input type="number" class="form-control" id="name" wire:model="amount"
                                placeholder="00.0" min="0">
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>{{-- Name Ar & wire:model="name_ar" --}}

                </div>{{-- End Row Right Items --}}

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name">{{ __('Note') }}</label>
                            <input type="text" class="form-control" id="name" wire:model="notes"
                                placeholder="{{ __('Note') }}">
                            @error('notes')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>{{-- Name EN & wire:model="name_en" --}}

                </div>

            </div>

            <div class="card-footer">
                <button class="btn btn-primary">
                    {{ __('save') }}
                    <i class="fa-solid fa-save"></i>
                </button>
            </div>

        </form>
    </div>
</div>
