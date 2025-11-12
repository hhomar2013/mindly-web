@if ($actions)
    @include('livewire.admins.cities.create_city')
@else
    <div class="card-header d-flex justify-content-between align-items-center">

        <h4 class="card-title">{{ $gov_name }}</h4>
        <div class="">
            <button wire:click="createCity" class="btn btn-success btbn-rounded">
                <i class="fa fa-plus"></i>
                {{ __('Add City') }}
            </button>
        </div>

        <hr>
    </div>
    <div class="card-body">

        <div class="table-responsive table-striped">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr class="text-center">
                        <th>{{ __('name') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th><i class="fa fa-cogs"></i></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cities_table as $city)
                        <tr class="text-center" wire:key="country-{{ $city->id }}">
                            <td>
                                {{ $city->name }}
                            </td>
                            <td>
                                @livewire('switcher', ['model' => $city, 'field' => 'status'], key($city->id))
                            </td>
                            <td>
                                <a wire:click="edit({{ $city->id }})" href="javascript:;">
                                    <i class="fa fa-edit text-info"></i>
                                </a> |
                                <a onclick="confirmDelete({{ $city->id }} ,'deleteCity')">
                                    <i class="fa-solid fa-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-danger p-5">{{ __('none results') }}</td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

    </div>
    <div class="card-footer text-white">
        <div class="pagination ">
            {{ $cities_table->links() }}
        </div>
    </div>
@endif
