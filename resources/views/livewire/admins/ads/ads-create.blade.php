<div class="card">
    <div class="card-body">
        <form wire:submit.prevent="saveAds">
            <div class="row">
                <div class="col-lg-8">
                    <div class="row">
                        {{-- 1. مصدر الإعلان --}}
                        <div class="col-lg-4 mb-3">
                            <label class="fw-bold">{{ __('Ads Source') }}</label>
                            <select class="form-control shadow-sm" wire:model.live="inOrOut">
                                <option value="in">{{ __('Internal (App)') }}</option>
                                <option value="out">{{ __('External (Link)') }}</option>
                            </select>
                        </div>

                        {{-- 2. نوع العرض --}}
                        <div class="col-lg-4 mb-3">
                            <label class="fw-bold">{{ __('Display Type') }}</label>
                            <select class="form-control shadow-sm" wire:model.live="type">
                                <option value="">{{ __('Select Type') }}</option>
                                @foreach ($types as $t)
                                <option value="{{ $t }}">{{ __(ucfirst($t)) }}</option>
                                @endforeach
                            </select>
                            @error('type') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        {{-- 3. حقل الرابط (للخارجي فقط) --}}
                        @if($inOrOut == 'out')
                        <div class="col-lg-4 mb-3">
                            <label class="fw-bold text-primary">{{ __('External Link') }}</label>
                            <input type="url" class="form-control shadow-sm" wire:model="link" placeholder="https://...">
                            @error('link') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        @endif
                    </div>

                    {{-- 4. حقول الوجهة الداخلية (Morph) --}}
                    @if($inOrOut == 'in')
                    <div class="row border-start border-3 border-success ms-1 mb-3">
                        <div class="col-lg-6">
                            <label class="fw-bold">{{ __('Ad To Type') }}</label>
                            <select class="form-control shadow-sm" wire:model.live="target_type">
                                <option value="">{{ __('Targeting...') }}</option>
                                <option value="teacher">{{ __('Teacher') }}</option>
                                <option value="center">{{ __('Center') }}</option>
                                <option value="course">{{ __('Course') }}</option>
                            </select>
                            @error('target_type') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        @if($target_type)
                        <div class="col-lg-6">
                            <label class="fw-bold">{{ __('Select Specific') }}</label>
                            <select class="form-control shadow-sm" wire:model="ad_to_id">
                                <option value="">{{ __('Choose item...') }}</option>
                                @if($target_type == 'teacher')
                                @foreach($teachers as $teacher) <option value="{{ $teacher->id }}">{{ $teacher->name }}</option> @endforeach
                                @elseif($target_type == 'center')
                                @foreach($centers as $center) <option value="{{ $center->id }}">{{ $center->name }}</option> @endforeach
                                @elseif($target_type == 'course')
                                @foreach($courses as $course) <option value="{{ $course->id }}">{{ $course->name }}</option> @endforeach
                                @endif
                            </select>
                            @error('ad_to_id') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label>{{ __('Start Date') }}</label>
                            <input type="date" class="form-control shadow-sm" wire:model="start_date">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label>{{ __('End Date') }}</label>
                            <input type="date" class="form-control shadow-sm" wire:model="end_date">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>{{ __('Comment') }}</label>
                        <textarea class="form-control shadow-sm" wire:model="comment" rows="2"></textarea>
                    </div>
                </div>

                {{-- 5. رفع الصورة --}}
                {{-- قسم رفع الصورة مع الـ Progress Bar --}}
                <div class="col-lg-4 text-center border-start">
                    <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true" x-on:livewire-upload-finish="uploading = false; progress = 0" x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">

                        <label for="image" class="d-block" style="cursor: pointer;">
                            <strong>{{ __('Ads Image') }}</strong>
                            <div class="mt-3 position-relative d-inline-block">
                                @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="rounded shadow" style="width: 200px; height: 200px; object-fit: cover;">
                                @elseif($old_image)
                                <img src="{{ asset('storage/' . $old_image) }}" class="rounded shadow" style="width: 200px; height: 200px; object-fit: cover;">
                                @else
                                <div class="bg-light rounded border d-flex align-items-center justify-content-center shadow-sm" style="width: 200px; height: 200px;">
                                    <i class="fa fa-image fa-3x text-muted"></i>
                                </div>
                                @endif
                            </div>
                            {{-- حقل الإدخال المخفي --}}
                            <input type="file" id="image" wire:model="image" hidden>
                        </label>

                        {{-- Progress Bar التصميم الجديد --}}
                        <div x-show="uploading" class="mt-3">
                            <div class="progress" style="height: 15px; border-radius: 10px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" :style="'width: ' + progress + '%'" x-text="progress + '%'">
                                </div>
                            </div>
                            <small class="text-muted mt-1 d-block">{{ __('Uploading image, please wait...') }}</small>
                        </div>

                        @error('image') <span class="text-danger d-block mt-2 small">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5 shadow-sm" wire:loading.attr="disabled">
                    <i class="fa fa-save me-1"></i> {{ __('Save Ads') }}
                </button>
                <button type="button" class="btn btn-outline-secondary px-4 ms-2 shadow-sm" wire:click="index">
                    {{ __('Cancel') }}
                </button>
            </div>
        </form>
    </div>
</div>
