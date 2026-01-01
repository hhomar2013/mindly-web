<div class="row p-4">
    <div class="col-lg-6">
        <h4> <i class="fa-solid fa-book-open"></i> {{ $subjectShow->name }} </h4> <br>
    </div>
    <div class="col-lg-6 text-end">
        @if (!$lesson_id)
            <button class="btn btn-danger btn-rounded" wire:click="$set('action','subject-managment')"> <i
                    class="fas fa-arrow-left"></i>
            </button>
        @else
            <button class="btn btn-danger btn-rounded" wire:click="subjectManagment({{ $subjectShow->id }})"> <i
                    class="fas fa-arrow-left"></i>
            </button>
        @endif
    </div>

    <div class="col-lg-12">
        <form wire:submit.prevent="saveLesson">
            <div class="card bg-gradient-white shadow p-2 text-white text-start min-h-100">

                <div class="card-body text-start">

                    <div class="row">
                        <div class="col-lg-4">
                            <label for="lesson_name"> {{ __('Arabic Name') }} </label>
                            <input type="text" wire:model="lesson_ar_name" class="form-control" id="lesson_name">
                            <label for="lesson_name"> {{ __('English Name') }} </label>
                            <input type="text" wire:model="lesson_en_name" class="form-control" id="lesson_name">
                        </div>
                    </div>
                    @error('lesson_name')
                        <span class="text-danger"> {{ $message }} </span>
                    @enderror
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary "> {{ __('save') }} </button>
                </div>

            </div>
        </form>
    </div>
</div>
