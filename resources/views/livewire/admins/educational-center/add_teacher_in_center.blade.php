<div class="card-body">
    <form wire:submit.prevent="SaveTeacherToCenter">
        <div class="row">
            @foreach ($teachers as $teacher)
                <div class="col-lg-2 col-md-4 col-sm-6 p-2">
                    <label for="teacher-{{ $teacher->id }}" class="form-check-label">
                        <div
                            class="card shadow mb-3 {{ in_array($teacher->id, (array) $teacher_id) ? 'bg-primary ' : '' }}">
                            <div class="card-body">
                                <img src="{{ asset('storage/' . $teacher->image) }}"
                                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                <h5
                                    class="text-center {{ in_array($teacher->id, (array) $teacher_id) ? 'text-white' : '' }}">
                                    <span style="display: inline-block; width: 150px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $teacher->name }}</span>
                                </h5>

                            </div>
                        </div>
                        <input type="checkbox" value="{{ $teacher->id }}" wire:model.live="teacher_id"
                            id="teacher-{{ $teacher->id }}" class="form-check-input" hidden>
                    </label>
                </div>
            @endforeach
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
