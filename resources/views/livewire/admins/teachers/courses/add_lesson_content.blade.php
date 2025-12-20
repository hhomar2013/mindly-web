<div class="row p-4">
    <div class="col-lg-6 text-start">
        <div class="">
            <i class="fa-solid fa-book-open"></i>
        </div>
    </div>
    <div class="col-lg-6 text-end">

        @if (!$lesson_id)
            <button class="btn btn-danger btn-rounded"
                wire:click="back('subject-managment','course_id' ,false,['lesson_content_ar_name','lesson_content_en_name','content_type_id','lesson_content_link','image'])">
                <i class="fas fa-arrow-left"></i>
            </button>
        @else
            <button class="btn btn-danger btn-rounded" wire:click="subjectManagment({{ $subjectShow->id }})"> <i
                    class="fas fa-arrow-left"></i>
            </button>
        @endif
    </div>


    <div class="col-lg-12">
        <form wire:submit.prevent="saveLessonsContent">
            <div class="card bg-gradient-white shadow p-2 text-white text-start min-h-100">
                <div class="card-body text-start">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="lesson_name"> {{ __('Arabic Name') }} </label>
                            <input type="text" wire:model="lesson_content_ar_name" class="form-control"
                                id="lesson_name">
                            @error('lesson_content_ar_name')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                            <label for="lesson_name"> {{ __('English Name') }} </label>
                            <input type="text" wire:model="lesson_content_en_name" class="form-control"
                                id="lesson_name">
                            @error('lesson_content_en_name')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror
                        </div>

                        <div class="col-lg-4">
                            <label for="content_type_id"> {{ __('Content Types') }} </label>
                            <select name="content_type_id" id="content_type_id" wire:model.live="content_type_id"
                                wire:change="getSelectedContentType" class="form-control">
                                <option value=""> {{ __('Select content type') }} </option>
                                @foreach ($contentTypes as $contentType)
                                    <option value="{{ $contentType->id }}" data-content="{{ $contentType->name }}">
                                        {{ $contentType->name }} </option>
                                @endforeach
                            </select>

                            @error('content_type_id')
                                <span class="text-danger"> {{ $message }} </span>
                            @enderror

                            @if ($selectedContentType == 'link')

                                <label for="">{{ __('Upload Video Link') }}</label>
                                <input type="text" class="form-control" wire:model="lesson_content_link" />
                                @error('lesson_content_link')
                                    <span class="text-danger"> {{ $message }} </span>
                                @enderror
                            @elseif($selectedContentType == 'image' || $selectedContentType == 'video')
                                <div class="w-full">
                                    <label for="image" class="btn btn-rounded">{{ __('Upload Image') }}
                                        <input type="file" name="" id="image" wire:model="image"
                                            hidden><br>
                                        @if ($image)
                                            <img src="{{ $image->temporaryUrl() }}"
                                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                        @elseif($old_image)
                                            <img src="{{ asset('storage/' . $old_image) }}"
                                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                        @else
                                            <img src="{{ asset('assets/img/mindly_icon.png') }}"
                                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                        @endif
                                    </label>
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>{{-- image & wire:model="image" --}}
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            @elseif($selectedContentType == 'quiz')
                                <label for="quiz_id"> {{ __('Quiz') }} </label>
                                <select name="" id="" wire:model.live="quiz_id" class="form-control">
                                    <option value="">{{ __('Select Quiz') }}</option>
                                    @foreach ($teacherQuiz as $quiz)
                                        <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>



                    </div>

                </div>
                <div class="card-footer">

                    <button class="btn btn-primary "> {{ __('save') }} </button>
                </div>

            </div>
        </form>
    </div>
    <hr>
    <div class="col-lg-12">
        <div class="card shadow">
            <div class="card-header"></div>

            <div class="card-body">
                <div class="row p-2">
                    @forelse ($lessons_content as $lesson_content)
                        <div class="col-lg-3 p-2">
                            <div class="card shadow-lg rounded bg bg-dark bg-opacity-10">
                                <div class="card-body text-center ">
                                    <h5 class="text-white">{{ $lesson_content->name }}</h5>
                                </div>
                                <div class="card-footer">
                                    {{-- <button class="btn btn-primary btn-rounded">
                                        <i class="fa-solid fa-sheet-plastic"></i>
                                    </button>&nbsp; --}}
                                    <button class="btn btn-success btn-rounded"
                                        wire:click="showLessonContent({{ $lesson_content->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-danger btn-rounded"
                                        onclick="confirmDelete({{ $lesson_content->id }} , 'deleteLessonContent')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>{{ __('No Lesson Content Found') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
