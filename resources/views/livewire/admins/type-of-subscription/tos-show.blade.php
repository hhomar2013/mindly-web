  <div class="card-header text-strat">
      <button class="btn btn-success" wire:click="createTos">
          <i class="fa fa-plus"></i>
          {{ __('Add New Subscription Type') }}</button>
  </div>
  <div class="card-body">
      <div class="row">
          @forelse ($typeOfSubscriptions as $typeOfSubscription)
              <div class="col-lg-3">
                  <div class="card bg-gradient-success">
                      <div class="card-body text-center">

                          <h6 class="card-title text-white">{{ $typeOfSubscription->name }}</h6>

                      </div>
                      <div class="card-footer">
                          <div class="d-flex justify-content-between align-items-center">
                              <div>

                                  <button class="btn btn-primary btn-rounded" wire:click="edit({{ $typeOfSubscription->id }})"><i
                                          class="fa-solid fa-edit"></i></button>
                                  <button class="btn btn-danger btn-rounded"
                                      onclick="confirmDelete({{ $typeOfSubscription->id }} ,'deletetypeOfSubscription')"><i
                                          class="fa-solid fa-trash"></i></button>
                              </div>
                              @livewire('switcher', ['model' => $typeOfSubscription, 'field' => 'status'], key($typeOfSubscription->id))
                          </div>
                      </div>
                  </div>
              </div>
          @empty
              <div class="col-lg-12">
                  <div class="card bg-danger">
                      <div class="card-body  text-center text-white">
                          <h5 class="card-title">{{ __('No Subscription Types Found') }}</h5>
                      </div>
                  </div>
              </div>
          @endforelse
      </div>

  </div>
