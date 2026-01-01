<div class="card-header text-strat">
    <button class="btn btn-success" wire:click="createCT">
        <i class="fa fa-plus"></i>
        {{ __('Add Content Types') }}</button>
</div>
<div class="card-body">
    <div class="row">
        @forelse ($contentTypes as $contentType)
            <div class="col-lg-3">
                <div class="card bg-gradient-success">
                    <div class="card-body text-center">
                        <img src="{{ $contentType->icon ? asset('storage/' . $contentType->icon) : asset('assets/img/mindly_icon.png') }}"
                            style="width: 100px; height: 100px; object-fit: fill; border-radius: 50%;">
                        <br />
                        <h6 class="card-title text-white">{{ $contentType->name }}</h6>

                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>

                                <button class="btn btn-primary" wire:click="edit({{ $contentType->id }})"><i
                                        class="fa-solid fa-edit"></i></button>
                                <button class="btn btn-danger"
                                    onclick="confirmDelete({{ $contentType->id }} ,'deleteContentType')"><i
                                        class="fa-solid fa-trash"></i></button>
                            </div>
                            @livewire('switcher', ['model' => $contentType, 'field' => 'status'], key($contentType->id))
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-lg-12">
                <div class="card bg-danger">
                    <div class="card-body  text-center text-white">
                        <h5 class="card-title">{{ __('No Content Types Found') }}</h5>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

</div>
