          <div class="card-body">
              <div class="card-header">
                  <button wire:click="$set('action','create')" class="btn btn-success btbn-rounded">
                      <i class="fa fa-plus"></i>
                      {{ __('Add Country') }}

                  </button>
              </div>
              <div class="table-responsive table-striped">
                  <table class="table align-items-center mb-0">
                      <thead>
                          <tr class="text-center">
                              <th>{{ __('name') }}</th>
                              <th>{{ __('Status') }}</th>
                              <th>{{ __('Number of Governors') }}</th>
                              <th><i class="fa-solid fa-images"></i></th>
                              <th><i class="fa fa-cogs"></i></th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody>
                          @forelse($countries as $country)
                          <tr class="text-center" wire:key="country-{{ $country->id }}">
                              <td>
                                  {{ $country->name }}
                              </td>
                              <td>
                                  @livewire('switcher', ['model' => $country, 'field' => 'status'], key($country->id))
                              </td>
                              <td>
                                  {{ $country->governors->count() }}
                              </td>
                              <td>
                                  <img src="{{ $country->image ? asset('storage/'. $country->image): asset('assets/img/mindly_icon.png') }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                              </td>
                              <td>
                                  <a wire:click="edit({{ $country->id }})" href="javascript:;">
                                      <i class="fa fa-edit text-info"></i>
                                  </a> |
                                  <a
                                  onclick="confirmDelete({{ $country->id }} ,'deleteCountry')"
                                   {{-- wire:click="delete({{ $country->id }})"  --}}
                                   >
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
          <div class="card-footer">
              <div class="pagination">
                  {{ $countries->links() }}
              </div>
          </div>
