<style>
    .stars button {
        transition: transform 0.15s ease;
    }

    .stars button:hover {
        transform: scale(1.2);
    }

    .stars i {
        transition: color 0.2s ease, transform 0.2s ease;
    }

    .stars i.glow {
        color: #ffc107 !important;
        transform: scale(1.25);
    }
</style>
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
                    <img class="bg-white" src="{{ asset('storage/' . $teacher->image) }}"
                        style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">

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
                    <button class="btn btn-danger btn-rounded"
                        onclick="confirmDelete({{ $teacher->id }},'deleteTeacher')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <br>
                    <div class="row">
                        <div class="form-group col-6 ">
                            <label for="" class="text-white"> {{ __('Status') }} </label>
                            @livewire('switcher', ['model' => $teacher, 'field' => 'state', 'dispatch' => 'refreshTeachers'], key('status-' . $teacher->id))
                        </div>

                        <div class="form-group col-6">
                            <label for="" class="text-white"> {{ __('In OR Out System') }} </label>
                            @livewire('switcher', ['model' => $teacher, 'field' => 'in_out', 'dispatch' => 'refreshTeachers'], key('inout-' . $teacher->id))
                        </div>


                    </div>
                    @if ($teacher->in_out == 1)
                        <div class="row">
                            <div class="col-12">
                                <label for="" class="text-white"> {{ __('System Rating') }} </label>
                                @if ($teacher->rating_system > 0)
                                    <button wire:click.prevent="clearRating({{ $teacher->id }})"
                                        class="btn btn-danger btn-rounded"><i class="fa-solid fa-eraser"></i></button>
                                @endif


                                <div class="stars">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <button class="border-0 bg-transparent"
                                            wire:click="rate({{ $teacher->id }}, {{ $i }})">
                                            <i
                                                class="fa-star {{ $teacher->rating_system >= $i ? 'fa-solid text-warning' : 'fa-regular text-white' }}"></i>
                                        </button>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        {{-- Rating system --}}
                    @endif

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


{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.star-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const teacherId = this.dataset.id;
                const value = this.dataset.value;

                Livewire.dispatch('rateTeacher', {
                    id: teacherId,
                    rating: value
                });
            });
        });
    });
</script> --}}
