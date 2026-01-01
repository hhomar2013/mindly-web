<div>
    <div class="row">
        <div class="col-12 min-vh-100">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <i class="fa-solid fa-earth-africa text-dark text-sm opacity-10"></i>
                        {{ __('cities') }}
                    </h5>
                    <div class="text-start">
                        @include('tools.spinner')
                    </div>

                </div>
                {{-- Countries --}}
                <div class="row p-4">
                    @foreach ($countries as $country)
                        <div class="col-lg-2 h6 ">
                            <a class="btn w-100 {{ $country_id == $country->id ? 'btn-warning' : 'btn-dark' }}"
                                wire:click="getCountry({{ $country->id }})">
                                <img src="{{ $country->image ? asset('storage/' . $country->image) : asset('assets/img/mindly_icon.png') }}"
                                    style="width: 100px; height: 100px; object-fit: fill; border-radius: 50%;">
                                <br />
                                {{ $country->name }}
                            </a>
                        </div>
                    @endforeach
                </div>


                @if ($country_id)
                    @include('livewire.admins.cities.city_gov')
                @endif

                {{-- end Countries --}}


            </div>
        </div>
    </div>
</div>

@script
    @include('tools.message')
@endscript
@script
    @include('tools.confimDelete', ['method' => 'deleteCity'])
@endscript
