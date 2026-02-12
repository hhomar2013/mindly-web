<div>
    <div class="row">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fa-solid fa-users text-dark "></i>
                        {{ __('Create User') }}
                    </h5>
                    @include('tools.spinner')
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="text-start">
                            <button class="btn btn-sm btn-rounded btn-danger"
                                wire:click.prevent="$set('action', 'index')">
                                <i class="fa fa-arrow-left"></i>
                            </button>
                        </div>

                    </div>
                    <div class="card-body text-left">
                        <form wire:submit.prevent="saveUser">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    wire:model="name">
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    wire:model="email">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('password') }}</label>
                                <input autocomplete="new-password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" wire:model="password">
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">{{ __('save') }}</button>
                            </div>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
