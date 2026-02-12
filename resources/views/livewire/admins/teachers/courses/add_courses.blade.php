<div class="row p-4">
    <div class="col-lg-4">
        <h3> {{ $teacher->name }} </h3>
    </div>
    <div class="col-lg-8 text-end">
        <button class="btn btn-danger btn-rounded" wire:click="back('show-course','course')">
            <i class="fas fa-arrow-left"></i>
        </button>
    </div>
    <hr>


    <div class="card shadow-lg bg-gradient-success ">
        <div class="card-body ">
            <div class="row text-white">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for=""> {{ __('Education Systems') }} </label>
                        <select name="" id="" class="form-control" wire:model.live="education_system_id"
                            wire:change="getEducationStages()">
                            <option> {{ __('Select education system') }} </option>
                            @foreach ($education_system as $item)
                                <option value="{{ $item->id }}"
                                    class="{{ $item->id == $education_system_id ? 'bg-success text-white' : '' }}">
                                    {{ $item->name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label for=""> {{ __('Education Stages') }} </label>
                        <select name="" id="" class="form-control" wire:model.live="education_stages_id"
                            wire:change="getSecondaryTrack()">
                            <option> {{ __('Select education stage') }} </option>
                            @foreach ($education_stages as $stages)
                                <option value="{{ $stages->id }}"
                                    class="{{ $stages->id == $education_stages_id ? 'bg-success text-white' : '' }}">
                                    {{ $stages->name }}</option>
                            @endforeach

                        </select>
                    </div>
                </div>

                @if ($secondary_tracks)
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for=""> {{ __('Secondary Tracks') }} </label>
                            <select name="" id="" class="form-control"
                                wire:model.live="secondary_tracks_id" wire:change="getScondaryGrades()">
                                <option> {{ __('Select secondary tracks') }} </option>
                                @foreach ($secondary_tracks as $tracks)
                                    <option value="{{ $tracks->id }}"
                                        class="{{ $tracks->id == $secondary_tracks_id ? 'bg-success text-white' : '' }}">
                                        {{ $tracks->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                @endif
                @if ($stage_grades)
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label> {{ __('Stage Grades') }} </label>
                            <select name="" id="" class="form-control" wire:model.live="stage_grades_id"
                                wire:change="getScondaryBranch()">
                                <option> {{ __('Select stage grade') }} </option>
                                @foreach ($stage_grades as $grades)
                                    <option value="{{ $grades->id }}"
                                        class="{{ $grades->id == $stage_grades_id ? 'bg-success text-white' : '' }}">
                                        {{ $grades->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                @endif
                @if ($secondary_branch)
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for=""> {{ __('Secondary Branch') }} </label>
                            <select name="" id="" class="form-control"
                                wire:model.live="secondary_branch_id" wire:change="getSecondarySubBranch()">
                                <option> {{ __('Select secondary branch') }} </option>
                                @foreach ($secondary_branch as $branch)
                                    <option value="{{ $branch->id }}"
                                        class="{{ $branch->id == $secondary_branch_id ? 'bg-success text-white' : '' }}">
                                        {{ $branch->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                @endif

                @if ($secondary_specializations)
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for=""> {{ __('Secondary Specializations') }} </label>
                            <select name="" id="" class="form-control"
                                wire:model.live="secondary_specializations_id">
                                <option> {{ __('Select secondary specializations') }} </option>
                                @foreach ($secondary_specializations as $specializations)
                                    <option value="{{ $specializations->id }}"
                                        class="{{ $specializations->id == $secondary_specializations_id ? 'bg-success text-white' : '' }}">
                                        {{ $specializations->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                @endif


                @if ($secondary_sub_branch)
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for=""> {{ __('Secondary Sub Branch') }} </label>
                            <select name="" id="" class="form-control"
                                wire:model.live="secondary_sub_branch_id">
                                <option> {{ __('Select secondary sub branch') }} </option>
                                @foreach ($secondary_sub_branch as $sub_branch)
                                    <option value="{{ $sub_branch->id }}"
                                        class="{{ $sub_branch->id == $secondary_sub_branch_id ? 'bg-success text-white' : '' }}">
                                        {{ $sub_branch->name }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>


    <form wire:submit.prevent="saveCourseOverview">
        <div class="card shadow-lg mt-4">
            <div class="card-body">
                <h5 class="mb-3">{{ __('Course Details') }}</h5>
                <div class="row">
                    <div class="col-10">
                        <div class="col-lg-6 mb-3">
                            <label for="course_name">{{ __('Course Name') }}</label>
                            <input type="text" id="course_name" class="form-control" wire:model="course_name"
                                required>
                            @error('course_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-lg-6 mb-3">

                            <label for="subject_id">{{ __('Subject') }}</label>
                            <select id="subject_id" class="form-control" wire:model="subject_id">
                                <option value="">{{ __('Select Subject') }}</option>

                                @foreach ($subjects_all as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label for="price">{{ __('Price') }}</label>
                            <input type="number" step="0.01" id="price" class="form-control"
                                wire:model="price">
                            @error('price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-lg-9 mb-3">
                            <label for="price_note">{{ __('Price Note (Optional)') }}</label>
                            <input type="text" id="price_note" class="form-control" wire:model="price_note">
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea id="description" class="form-control" rows="3" wire:model="description"></textarea>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label for="optional_link">{{ __('Optional Link') }}</label>
                            <input type="url" id="optional_link" class="form-control"
                                wire:model="optional_link">
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="image" class="btn">{{ __('Course Image') }}
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
                        </div>
                    </div>

                </div> {{-- End row --}}

                <div class="text-start">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        {{ __('Save Course') }}
                    </button>
                </div>

            </div>
        </div>
    </form>

</div>
