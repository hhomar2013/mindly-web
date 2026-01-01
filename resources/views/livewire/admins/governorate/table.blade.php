          <div class="card-body">
              <div class="card-header">
                  <button wire:click="switchAction('create')" class="btn btn-success btbn-rounded">
                      <i class="fa fa-plus"></i>
                      {{ __('Add Governorate') }}

                  </button>
              </div>
              <div class="table-responsive table-striped">
                  <table class="table align-items-center mb-0">
                      <thead>
                          <tr class="text-center">
                              <th><i class="fa-solid fa-earth-africa text-dark text-sm opacity-10"></i></th>
                              <th>{{ __('name') }}</th>
                              <th>{{ __('Status') }}</th>
                              <th><i class="fa fa-cogs"></i></th>
                              <th></th>
                          </tr>
                      </thead>
                      <tbody>
                          @forelse($governors as $gover)
                          <tr class="text-center" wire:key="gover-{{ $gover->id }}">
                              <td>
                                  {{ $gover->country->name }}
                              </td>
                              <td>
                                  {{ $gover->name }}
                              </td>
                              <td>
                                  @livewire('switcher', ['model' => $gover, 'field' => 'status'], key($gover->id))
                              </td>
                              <td>
                                  <a wire:click="edit({{ $gover->id }})" href="javascript:;">
                                      <i class="fa fa-edit text-info"></i>
                                  </a> |
                                  <a onclick="confirmDelete({{ $gover->id }} , 'deleteGovernorate')" {{-- wire:click="delete({{ $gover->id }})" --}}>
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
                  {{ $governors->links() }}
              </div>
          </div>


@include('tools.confimDelete', ['method' => 'deleteGovernorate'])
