<div class="card-body">
    <style>
        .banner_image {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }
    </style>
    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-lg-12">
                <label for="banner" style="width: 100% ; height: 250;">
                    <input type="file" wire:model="banner" hidden id="banner" />
                    @if ($banner)
                        <img src="{{ $this->getPreviewUrl($banner) }}" class="rounded banner_image">
                    @elseif($old_banner)
                        <img src="{{ asset('storage/' . $old_banner) }}" class="rounded banner_image">
                    @else
                        <img src="{{ asset('assets/img/banner.png') }}" class="rounded banner_image">
                    @endif
                </label>

            </div>
            <hr>
            <div class="col-lg-8">
                {{-- Row Right Items --}}
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name">{{ __('Arabic Name') }}</label>
                            <input type="text" class="form-control" id="name" wire:model="name_ar"
                                placeholder="{{ __('Enter educational center name') }}">
                            @error('name_ar')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>{{-- Name Ar & wire:model="name_ar" --}}
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="name">{{ __('English Name') }}</label>
                            <input type="text" class="form-control" id="name" wire:model="name_en"
                                placeholder="{{ __('Enter educational center name') }}">
                            @error('name_en')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>{{-- Name EN & wire:model="name_en" --}}
                    <div class="col-lg-4">
                        <label for=""><i class="fa fa-phone"></i>{{ __('Teacher Phone') }}</label>
                        <input type="text" wire:model="phone" class="form-control w-100"
                            placeholder="{{ __('Enter Teacher Phone') }}">
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>{{-- Teacher Phone & wire:model="phone" --}}
                    <div class="col-lg-8"></div>
                    <hr>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">{{ __('Countries') }}</label>
                            <select wire:model.live="country_id" class="form-control w-100">
                                <option value="">{{ __('Select Country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}"
                                        class="{{ $country_id == $country->id ? 'bg-success text-white' : '' }}">
                                        {{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>{{-- Country & wire:model="country_id" --}}
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">{{ __('Governors') }}</label>
                            <select wire:model.live="governor_id" class="form-control w-100 ">
                                <option value="">{{ __('select governorate') }}</option>
                                @foreach ($governorates as $governorate)
                                    <option value="{{ $governorate->id }}"
                                        class="{{ $governor_id == $governorate->id ? 'bg-success text-white' : '' }}">
                                        {{ $governorate->name }}</option>
                                @endforeach
                            </select>
                            @error('governor_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>{{-- Governorate & wire:model="governor_id" --}}
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">{{ __('cities') }}</label>
                            <select wire:model.live="city_id" class="form-control w-100">
                                <option value="">{{ __('Select City') }}</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}"
                                        class="{{ $city_id == $city->id ? 'bg-success text-white' : '' }}">
                                        {{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>{{-- City & wire:model="city_id" --}}
                    <div class="col-lg-8">
                    </div>{{-- Country & governors & Cities --}}
                    <div class="col-lg-12">
                        <label for="">{{ __('Teacher Address') }}</label>
                        <textarea wire:model="address" name="" id="" cols="20" rows="2" class="form-control w-100"
                            placeholder="{{ __('Enter Teacher Address') }}"></textarea>
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>{{-- Teacher Address  & wire:model="address" --}}
                    <div class="col-lg-12">
                        <label for="">{{ __('Teacher Details') }}</label>
                        <textarea wire:model="description" name="" id="" cols="10" rows="2"
                            class="form-control w-100" placeholder="{{ __('Enter Teacher Details') }}"></textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>{{-- Teacher Details  & wire:model="description" --}}
                    <div class="col-lg-12">
                        <br>
                        <h5 class="text-danger"> {{ __('Social Media Types') }} </h3>
                            @foreach ($socialMediaTypes as $socialMediaType)
                                <div class="form-group">
                                    <label for="">{{ $socialMediaType->name }}</label>
                                    <input type="text" wire:model="teacherSMList.{{ $socialMediaType->id }}"
                                        class="form-control w-100"
                                        placeholder="{{ __('Enter Link') . ' ' . $socialMediaType->name }}">
                                    @error('teacherSMList.' . $socialMediaType->id)
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach


                    </div>{{-- End of Social Media Types --}}
                </div>{{-- End Row Right Items --}}
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="image" class="btn">{{ __('Teacher Image') }}
                        <input type="file" name="" id="image" wire:model="image" hidden><br>
                        @if ($image)
                            <img src="{{ $this->getPreviewUrl($image) }}"
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
