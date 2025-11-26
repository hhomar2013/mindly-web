      <div class="card-body">
          <form wire:submit.prevent="store">
              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="name">{{ __('Teachers') }}</label>
                          <select name="" id=""
                              class="form-control @error('selectedTeacher') is-invalid @enderror"
                              wire:model.live="selectedTeacher">
                              <option value="">{{ __('Select Teacher') }}</option>
                              @foreach ($teachers as $teacher)
                                  <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                              @endforeach
                          </select>
                          @error('selectedTeacher')
                              <span class="text-danger">{{ $message }}</span>
                          @enderror
                      </div>
                  </div>


                  <div class="col-md-4">
                      <div class="form-group">
                          <label for="name">{{ __('Courses') }}</label>
                          <select name="" id=""
                              class="form-control @error('selectedCourse') is-invalid @enderror"
                              wire:model.live="selectedCourse">
                              <option value="">{{ __('Select Course') }}</option>
                              @foreach ($courses as $course)
                                  <option value="{{ $course->id }}">{{ $course->name }}</option>
                              @endforeach
                          </select>
                          @error('selectedCourse')
                              <span class="text-danger">{{ $message }}</span>
                          @enderror
                      </div>
                  </div>
                  @if ($selectedTeacher)
                      <div class="col-lg-4">
                          <div class="card shadow-lg mb-3 bg-gradient-success">
                              <div class="card-body text-center">
                                  <img src="{{ asset('storage/' . $teacherInfo->image) }}" alt=""
                                      class="img-fluid rounded-circle" style="width: 100px; height: 100px">
                                  <h5 class="text-white">{{ $teacherInfo->name }}</h5>
                                  <p class="text-white"><strong>{{ __('Balance') }} :
                                          {{ $teacherInfo->wallet->balance }}&nbsp;
                                          {{ __('EGP') }}</strong>
                                  </p>
                              </div>
                          </div>
                      </div>
                  @endif

              </div>
              @if ($selectedCourse)

                  <div class="card ">
                      <div class="card-body ">
                          <div class="row">

                              <div class="col-lg-4">

                                  <div class="form-group">
                                      <label for="name">{{ __('Type of Subscriptions') }}</label>
                                      <select name="" id=""
                                          class="form-control @error('subscriptionId') is-invalid @enderror"
                                          wire:model="subscriptionId">
                                          <option value="">{{ __('Select type of subscription') }}</option>
                                          @foreach ($typeOfSubscription as $typeOfSubscriptionVal)
                                              <option value="{{ $typeOfSubscriptionVal->id }}">
                                                  {{ $typeOfSubscriptionVal->name }}
                                              </option>
                                          @endforeach
                                      </select>
                                      @error('subscriptionId')
                                          <span class="text-danger">{{ $message }}</span>
                                      @enderror
                                  </div>

                              </div>

                              <div class="col-lg-4">
                                  <div class="form-group">
                                      <label for="name">{{ __('Count of codes') }}</label>
                                      <input type="number" min="1" name="" id=""
                                          class="form-control @error('codeCount') is-invalid @enderror"
                                          wire:model="codeCount">
                                  </div>
                                  @error('codeCount')
                                      <span class="text-danger">{{ $message }}</span>
                                  @enderror
                              </div>

                              <div class="col-lg-4">
                                  <div class="form-group">
                                      <label for="name">{{ __('Code Price') }}</label>
                                      <input type="number" min="0" name="" id=""
                                          class="form-control @error('codePrice') is-invalid @enderror"
                                          wire:model="codePrice" placeholder="{{ __('Enter code price') }}">
                                  </div>
                                  @error('codePrice')
                                      <span class="text-danger">{{ $message }}</span>
                                  @enderror
                              </div>

                              <div class="col-lg-12">
                                  <button class="btn btn-success mt-3" wire:click.prevent="addCodeToList">
                                      <i class="fa fa-plus"></i>

                                  </button>
                              </div>
                          </div>


                      </div>

                      <div class="card-footer">
                          <div class="row">

                              @foreach ($CreationCodeList as $CreationCodeListVal)
                                  <div class="col-lg-4 ">
                                      <div class="card bg-gradient-warning text-white ">
                                          <div class="card-body text-center">
                                              <h3 class="text-white">{{ $CreationCodeListVal['subscriptionName'] }}
                                              </h3>
                                              <h5 class="text-white">
                                                  {{ __('Count of codes') }}: {{ $CreationCodeListVal['code_count'] }}
                                              </h5>
                                              <h5 class="text-white">
                                                  {{ __('Code Price') }}: {{ $CreationCodeListVal['code_price'] }}
                                              </h5>
                                              {{-- <button wire:click.prevent="editCodeList({{ $loop->index }})"
                                                  class="btn btn-primary btn-sm">
                                                  <i class="fa fa-edit"></i>
                                              </button> --}}
                                              <button wire:click.prevent="removeFromCodeList({{ $loop->index }})"
                                                  class="btn btn-danger btn-sm">
                                                  <i class="fa fa-trash"></i>
                                              </button>
                                          </div>
                                      </div>
                                  </div>
                              @endforeach

                          </div>


                      </div>
                      @if ($totalPrice > 0)
                          <br>
                          <div class="col-lg-4">
                              <h4 class="text-dark"><strong>{{ __('Total Price') }} :
                                      {{ $totalPrice }}&nbsp;
                                      {{ __('EGP') }}</strong>
                              </h4>
                          </div>
                      @endif
                  </div>
              @else
              @endif


              <button type="submit" class="btn btn-primary mt-3">
                  <i class="fa fa-save"></i>
                  {{ __('save') }}
              </button>
              &nbsp;&nbsp;
              <button wire:click.prevent="back" class="btn btn-warning btbn-rounded mt-3">
                  <i class="fa fa-arrow-left"></i>
              </button>

          </form>
      </div>
