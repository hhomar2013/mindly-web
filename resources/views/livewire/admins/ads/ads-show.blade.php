<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <button class="btn btn-success btn-sm" wire:click="create()">
                <i class="fa fa-plus"></i>
                {{ __('Add Ads') }}</button>
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse ($ads as $ads_val)
                <div class="col-lg-3 p-3">
                    <div class="card shadow bg-gradient-secondary text-white">
                        <div class="card-body text-center">
                            <img class="bg-white" src="{{ asset('storage/' . $ads_val->image) }}"
                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">

                            <h5 class="card-title">{{ $ads_val->type == 'sliders' ? __('Sliders') : __('Popup') }}</h5>
                            {{-- <p class="card-text">Content</p> --}}
                        </div>
                        <div class="card-footer">

                            <button class="btn btn-primary btn-rounded" wire:click.prevent="edit({{ $ads_val->id }})">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-rounded"
                                onclick="confirmDelete({{ $ads_val->id }},'deleteAds')">
                                <i class="fa-solid fa-trash"></i>
                            </button>

                            @livewire('switcher', ['model' => $ads_val, 'field' => 'status'], key($ads_val->id))
                        </div>

                    </div>
                </div>

            @empty
                <div class="col-lg-12 p-4">
                    <div class="card bg-danger text-white text-center">
                        <div class="card-body">
                            <h5 class="card-title">{{ __('none results') }}</h5>
                        </div>
                    </div>
                </div>
            @endforelse


        </div>
    </div>
</div>
