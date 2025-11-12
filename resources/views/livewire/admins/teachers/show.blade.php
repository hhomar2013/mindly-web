<div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="card-title">
        <button class="btn btn-success btn-rounded" wire:click.prevent="createTeacher()">
            <i class="fa-solid fa-plus"></i>
            {{ __('Add Teacher') }}</button>
    </h5>
</div>
<div class="row">
    @forelse ($teachers as $teacher)
    <div class="col-lg-3 p-3">
        <div class="card shadow bg-gradient-secondary text-white">
            <div class="card-body text-center">
                <img class="bg-white" src="{{  asset('storage/' . $teacher->image) }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">

                <h5 class="card-title">{{ $teacher->name }}</h5>
                {{-- <p class="card-text">Content</p> --}}
            </div>
            <div class="card-footer">
                {{-- <button class="btn btn-success btn-rounded" wire:click.prevent="edit({{ $teacher->id }})">
                    <i class="fa-solid fa-person-chalkboard"></i>
                </button> --}}
                <button class="btn btn-primary btn-rounded" wire:click.prevent="edit({{ $teacher->id }})">
                    <i class="fa-solid fa-edit"></i>
                </button>
                <button class="btn btn-danger btn-rounded" onclick="confirmDelete({{ $teacher->id }},'deleteTeacher')">
                    <i class="fa-solid fa-trash"></i>
                </button>
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
            {{ $teachers->links() }}
        </div>
    </div>
</div>

