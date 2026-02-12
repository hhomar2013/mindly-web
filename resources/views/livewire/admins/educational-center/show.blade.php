<div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title">
        <button class="btn btn-success btn-rounded" wire:click.prevent="createCenter()">
            <i class="fa-solid fa-plus"></i>
            {{ __('Add Educational Center') }}</button>
    </h5>
</div>
<div class="row">
    @forelse ($centers as $center)
    <div class="col-lg-3 p-3">
        <div class="card shadow bg-gradient-secondary text-white">
            <div class="card-body text-center">
                <img class="bg-white" src="{{  asset('storage/' . $center->logo) }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">

                <h5 class="card-title">{{ $center->name }}</h5>
                {{-- <p class="card-text">Content</p> --}}
            </div>
            <div class="card-footer">
                <button class="btn btn-success btn-rounded" wire:click.prevent="showTeachers({{ $center->id }})">
                    <i class="fa-solid fa-person-chalkboard"></i>
                </button>
                <button class="btn btn-primary btn-rounded" wire:click.prevent="edit({{ $center->id }})">
                    <i class="fa-solid fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-rounded" onclick="confirmDelete({{ $center->id }},'deleteCenter')">
                    <i class="fa-solid fa-trash"></i>
                </button>

                @livewire('switcher', ['model' => $center, 'field' => 'state'], key($center->id))
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

    <div class="card-footer">
        <div class="pagination">
            {{ $centers->links() }}
        </div>
    </div>
</div>

