<div class="card-body">
    <div class="col-lg-6 p-4">
        <label for="">{{ __('search') }}</label>
        <input type="text" class="form-control" wire:model.live="search" />
    </div>
    <div class="row p-4">
        @foreach ($teachers as $teacher)
            <div class="col-lg-2 h6 ">
                <a class="btn w-100 {{ $teacher_id === $teacher->id ? 'btn-warning' : 'btn-dark' }}"
                    wire:click="showTeacherQuiz({{ $teacher->id }})">
                    <img src="{{ $teacher->image ? asset('storage/' . $teacher->image) : asset('assets/img/mindly_icon.png') }}"
                        style="width: 100px; height: 100px; object-fit: fill; border-radius: 50%;">
                    <br />
                    {{ $teacher->name }}
                </a>
            </div>
        @endforeach
    </div>
</div>
