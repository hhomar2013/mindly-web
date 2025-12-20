      <div class="card-body">
          <form wire:submit.prevent="save">
              <div class="row">
                  <div class="col-12">
                      <h4> {{ $quizTitle }}</h4>&nbsp;&nbsp;
                  </div>
                  <div class="col-md-12">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="name"><i class="fa-regular fa-keyboard"></i> {{ __('Quiz Title') }}</label>
                              <input type="text" class="form-control @error('quizTitle') is-invalid @enderror"
                                  id="name" wire:model.live="quizTitle" placeholder="">
                              @error('quizTitle')
                                  <span class="text-danger">{{ $message }}</span>
                              @enderror
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-md-2">
                              <div class="form-group">
                                  <label for="name"><i
                                          class="fa-regular fa-alarm-clock"></i>{{ __('Quiz Duration in minutes') }}</label>
                                  <input type="number" min="1"
                                      class="form-control @error('quizDuration') is-invalid @enderror" id="name"
                                      wire:model="quizDuration" placeholder="">
                                  @error('quizDuration')
                                      <span class="text-danger">{{ $message }}</span>
                                  @enderror
                              </div>
                          </div>
                            {{-- <div class="col-md-2">
                                <div class="form-group">
                                    <label for="name"> <i class="fa-solid fa-hashtag"></i>
                                        {{ __('Quiz Questions Count') }}</label>
                                    <input type="number" min="1"
                                        class="form-control @error('quizQuestionsCount') is-invalid @enderror"
                                        id="name" wire:model="quizQuestionsCount" placeholder="">
                                    @error('quizQuestionsCount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="name"> <i class="fa-solid fa-calculator"></i>
                                        {{ __('Quiz Total Score') }}</label>
                                    <input type="number" min="1"
                                        class="form-control @error('quizTotalScore') is-invalid @enderror" id="name"
                                        wire:model="quizTotalScore" placeholder="">
                                    @error('quizTotalScore')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> --}}
                      </div>


                      <div class="col-md-12">
                          <button type="submit" class="btn btn-primary mt-3">
                              <i class="fa fa-save"></i>
                              {{ __('save') }}
                          </button>
                          &nbsp;&nbsp;
                          <button wire:click.prevent="back('show-teacher-quizs',['teacher_id_create_quiz'],[])"
                              class="btn btn-warning mt-3">
                              <i class="fa fa-arrow-left"></i>
                          </button>
                      </div>


                  </div>


          </form>
      </div>
