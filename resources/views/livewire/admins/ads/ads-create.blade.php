<div class="card">
    <div class="card-body">
        <form wire:submit.prevent="saveAds">
            <div class="row">
                <div class="col-lg-3 mb-3 col-auto">
                    {{-- نوع نشر الاعلان --}}
                    <label class="fw-bold">{{ __('Type Of Ad Posting') }}</label>
                    <select class="form-control shadow-sm" wire:model.live="typeOfAdPostingSelected">
                        <option value="" selected>{{ __('Select Type') }}</option>
                        @foreach ($typeOfAdPosting as $t)
                            <option value="{{ $t }}">{{ __($t) }}</option>
                        @endforeach
                    </select>
                    @error('typeOfAdPosting')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror

                </div>

            </div>{{-- Source of ad posting --}}
            <div {{ $typeOfAdPostingSelected <= 0 ? 'hidden' : '' }}>
                <div class="row">
                    <div class="col-lg-8">

                        <div class="row">


                            {{-- 1. مصدر الإعلان --}}
                            <div class="col-lg-4 mb-3">
                                <label class="fw-bold">{{ __('Ads Source') }}</label>
                                <select class="form-control shadow-sm" wire:model.live="inOrOut">
                                    <option value="">{{ __('Select Source Of Ad') }}</option>
                                    <option value="in">{{ __('Internal (App)') }}</option>
                                    <option value="out">{{ __('External (Link)') }}</option>
                                </select>
                                @error('inOrOut')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            @if ($typeOfAdPostingSelected == 'ads' || $typeOfAdPostingSelected == 'adsAndNotifications')
                                {{-- نوع العرض --}}
                                <div class="col-lg-4 mb-3">
                                    <label class="fw-bold">{{ __('Display Type') }}</label>
                                    <select class="form-control shadow-sm" wire:model.live="type">
                                        <option value="">{{ __('Select Type') }}</option>
                                        @foreach ($types as $t)
                                            <option value="{{ $t }}">{{ __(ucfirst($t)) }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif


                            @if ($inOrOut == 'out')
                                <div class="col-lg-4 mb-3">
                                    <label class="fw-bold text-primary">{{ __('External Link') }}</label>
                                    <input type="url" class="form-control shadow-sm" wire:model="link"
                                        placeholder="https://...">
                                    @error('link')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        @if ($inOrOut == 'in')
                            <div class="row border-start border-3 border-success ms-1 mb-3">
                                <div class="col-lg-6">
                                    <label class="fw-bold">{{ __('Ad To Type') }}</label>
                                    <select class="form-control shadow-sm" wire:model.live="target_type">
                                        <option value="">{{ __('Select Ads To') }}</option>
                                        <option value="general">{{ __('General') }}</option>
                                        <option value="teacher">{{ __('Teachers') }}</option>
                                        <option value="center">{{ __('Education Centers') }}</option>
                                        <option value="course">{{ __('Courses') }}</option>
                                    </select>
                                    @error('target_type')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                @if ($target_type && $target_type != 'general')
                                    <div class="col-lg-6">
                                        <label class="fw-bold">{{ __('Select Specific') }}</label>
                                        <select class="form-control shadow-sm" wire:model="ad_to_id">
                                            <option value="">{{ __('Choose item...') }}</option>
                                            @if ($target_type == 'teacher')
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                @endforeach
                                            @elseif($target_type == 'center')
                                                @foreach ($centers as $center)
                                                    <option value="{{ $center->id }}">{{ $center->name }}</option>
                                                @endforeach
                                            @elseif($target_type == 'course')
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('ad_to_id')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        @endif
                        <div class="card shadow-lg  mb-3">
                            <div class="card-body">
                                <div class="row p-3 border-start border-3 border-warning">
                                    <div class="col-lg-4 mb-3">
                                        <label class="fw-bold text-primary">{{ __('Countries') }}</label>
                                        <select class="form-control shadow-sm" wire:model.live="country_id"
                                            wire:loading.attr="disabled">
                                            <option value="0">{{ __('Select Country') }}</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('country_id')
                                            <span class="text-danger small">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if ($this->country_id > 0)
                                        <div class="col-lg-4 mb-3">
                                            <label class="fw-bold text-primary">{{ __('Governors') }}</label>
                                            <select class="form-control shadow-sm" wire:model.live="governorate_id"
                                                wire:loading.attr="disabled">
                                                <option value="0">{{ __('select governorate') }}</option>
                                                @foreach ($governorates as $governorate)
                                                    <option value="{{ $governorate->id }}">{{ $governorate->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('governorate_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                    @if ($this->governorate_id > 0 && $this->country_id > 0)
                                        <div class="col-lg-4 mb-3">
                                            <label class="fw-bold text-primary">{{ __('cities') }}</label>
                                            <select class="form-control shadow-sm" wire:model="city_id"
                                                wire:loading.attr="disabled">
                                                <option value="0">{{ __('Select City') }}</option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('city_id')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>


                                    @endif
                                </div>
                            </div>

                        </div>

                        @switch($typeOfAdPostingSelected)
                            @case('notifications')
                                @include('livewire.admins.ads.create-notifications')
                            @break

                            @case('adsAndNotifications')
                                @include('livewire.admins.ads.create-notifications')
                                @include('livewire.admins.ads.create-ads')
                            @break

                            @case('ads')
                                @include('livewire.admins.ads.create-ads')
                            @break
                        @endswitch
                    </div>


                    <div class="col-lg-4 text-center border-start">
                        <div class="row">
                            <div class="col-lg-12">
                                <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                                    x-on:livewire-upload-finish="uploading = false; progress = 0"
                                    x-on:livewire-upload-error="uploading = false"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress">

                                    <label for="image" class="d-block" style="cursor: pointer;">
                                        <strong>{{ __('Ads Image') }}</strong>
                                        <div class="mt-3 position-relative d-inline-block">
                                            @if ($image)
                                                <img src="{{ $this->getPreviewUrl($image) }}" class="rounded shadow"
                                                    style="width: 200px; height: 200px; object-fit: cover;">
                                            @elseif($old_image)
                                                <img src="{{ asset('storage/' . $old_image) }}" class="rounded shadow"
                                                    style="width: 200px; height: 200px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded border d-flex align-items-center justify-content-center shadow-sm"
                                                    style="width: 200px; height: 200px;">
                                                    <i class="fa fa-image fa-3x text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="file" id="image" wire:model="image" hidden>
                                    </label>

                                    <div x-show="uploading" class="mt-3">
                                        <div class="progress" style="height: 15px; border-radius: 10px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                role="progressbar" :style="'width: ' + progress + '%'"
                                                x-text="progress + '%'">
                                            </div>
                                        </div>
                                        <small
                                            class="text-muted mt-1 d-block">{{ __('Uploading image, please wait...') }}</small>
                                    </div>

                                    @error('image')
                                        <span class="text-danger d-block mt-2 small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @if ($typeOfAdPostingSelected == 'notifications' || $typeOfAdPostingSelected == 'adsAndNotifications')
                                <div class="col-lg-12">
                                    <div class="card bg-dark mt-5">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between ">
                                                <div class="d-flex flex-column">
                                                    <p class="fw-bold text-white m-0 text-start">
                                                        {{ $notification_title ?? __('Notification Title') }}
                                                    </p>
                                                    <small class="text-white text-start">
                                                        {{ $notification_text ?? __('Notification Text') }}
                                                    </small>
                                                </div>
                                                {{-- <button class="btn btn-outline-light ">
                                                    <i class="fa fa-arrow-left me-1"></i>
                                                    {{ __('Ad') }}
                                                </button> --}}
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>

                    </div>





                </div>


                {{-- Actions buttons --}}
                <div class="mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm" wire:loading.attr="disabled">
                        <i class="fa fa-save me-1"></i> {{ __('save') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary px-4 ms-2 shadow-sm" wire:click="index">
                        {{ __('cancel') }}
                    </button>
                </div>
                {{-- End Actions buttons --}}
            </div>


        </form>
    </div>
</div>
