<div class="card">
    <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
        x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
        x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
        <div class="card-body">
            <form action="" wire:submit.prevent="saveAds">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-4 mb-3">
                                <label for="course_name">{{ __('Ads Type') }}</label>
                                <select id="course_name" class="form-control" wire:model.live="type">
                                    <option value="">{{ __('Select Ads Type') }}</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type }}">
                                            {{ $type == 'sliders' ? __('Sliders') : __('Popup') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label for="ads_to_id">{{ __('Ads To') }}</label>
                                <select id="ads_to_id" class="form-control" wire:model.live="ads_to_id">
                                    <option value="">{{ __('Select Ads To') }}</option>
                                    @php
                                        $labels = [
                                            'teacher' => __('Teachers'),
                                            'center' => __('Education Centers'),
                                            'course' => __('Courses'),
                                        ];
                                    @endphp
                                    @foreach ($ads_to as $val)
                                        <option value="{{ $val }}">
                                            {{ $labels[$val] ?? $val }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ads_to')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>


                        @if ($ads_to_id == 'teacher')
                            <div class="col-lg-4 mb-3">
                                <label for="teacher_id">{{ __('Teachers') }}</label>
                                <select id="teacher_id" class="form-control" wire:model.live="teacher_id">
                                    <option value="">{{ __('Select Teacher') }}</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        {{-- Teacher Section --}}

                        @if ($ads_to_id == 'center')
                            <div class="col-lg-4 mb-3">
                                <label for="center_id">{{ __('Education Centers') }}</label>
                                <select id="center_id" class="form-control" wire:model.live="center_id">
                                    <option value="">{{ __('Select Center') }}</option>
                                    @foreach ($centers as $center)
                                        <option value="{{ $center->id }}">{{ $center->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        {{-- center section --}}
                        @if ($ads_to_id == 'course')
                            <div class="col-lg-4 mb-3">
                                <label for="course_id">{{ __('Courses') }}</label>
                                <select id="course_id" class="form-control" wire:model.live="course_id">
                                    <option value="">{{ __('Select Course') }}</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        {{-- center section --}}

                        <div class="col-lg-8 mb-3">
                            <label for="optional_link">{{ __('Ads Comment') }}</label>
                            <input type="text" id="optional_link" class="form-control" wire:model="comment">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label for="optional_link">{{ __('Ads Comment') }}</label>
                            <input type="text" id="optional_link" class="form-control" wire:model="comment">
                        </div>
                    </div>{{-- Right Section --}}

                    <div class="col-lg-4">

                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="image" class="btn">{{ __('Ads Image') }}
                                    <input type="file" name="" id="image" wire:model="image" hidden><br>
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
                    </div>{{-- Left Section --}}

                </div> {{-- End row --}}

                <div class="text-start">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        {{ __('Save Ads') }}
                    </button>
                    <button class="btn btn-danger" wire:click.prevent="index()">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                </div>
            </form>
        </div>


    </div>
