         <style>
             .form-switch {
                 display: inline-flex !important;
                 align-items: center !important;
             }
         </style>
         <div class="card-body">
             <form wire:submit.prevent="{{ $update ? 'updateQuestion' : 'saveQuestion' }}">

                 <div class="row p-2">

                     {{-- ================= Question Type ================= --}}
                     <div class="col-md-3">
                         <label>{{ __('Question Types') }}</label>
                         <select class="form-control" wire:model.live="questionType" wire:change="addQuestionAnswer">
                             <option value="">{{ __('Select Question Type') }}</option>
                             @foreach ($questionTypes as $key => $qType)
                                 <option value="{{ $key }}">
                                     {{ $qType == 'True Or False' ? __('True Or False') : __('Choose One') }}
                                 </option>
                             @endforeach
                         </select>
                     </div>

                     {{-- ================= Question Title ================= --}}
                     <div class="col-md-6">
                         <div class="form-group">
                             <label>{{ __('Question Title') }}</label>
                             <input type="text" class="form-control" wire:model="questionTitle">
                         </div>
                     </div>

                     {{-- ================= Question Score ================= --}}
                     <div class="col-md-3">
                         <div class="form-group">
                             <label>{{ __('Question Score') }}</label>
                             <input type="text" class="form-control" wire:model="answerScore">
                         </div>
                     </div>

                     {{-- ================= Question Image ================= --}}
                     <div class="col-md-12 mt-3">
                         <p>{{ __('Question Image') }}</p>

                         <div class="form-check form-switch text-center" dir="ltr">
                             <input class="form-check-input" type="checkbox" role="switch" id="toggle-isImage"
                                 wire:model.live="isImage" name="toggle-isImage">
                             <label class="form-check-label" id="toggle-isImage" for="toggle-isImage"></label>
                         </div>

                         @if ($isImage)
                             <div class="form-group">
                                 <label for="image" class="btn">
                                     <input type="file" name="" id="image" wire:model="questionImage"
                                         hidden><br>
                                     @if ($questionImage)
                                         <img src="{{ $questionImage->temporaryUrl() }}"
                                             style="width: 400px; height: 400px; object-fit: cover; border-radius: 10%;">
                                     @elseif($old_questionImage)
                                         <img src="{{ asset('storage/' . $old_questionImage) }}"
                                             style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%;">
                                     @else
                                         <img src="{{ asset('assets/img/mindly_icon.png') }}"
                                             style="width: 400px; height: 400px; object-fit: cover; border-radius: 10%;">
                                     @endif
                                 </label>
                                 @error('image')
                                     <span class="text-danger">{{ $message }}</span>
                                 @enderror
                             </div>
                         @endif
                     </div>

                     {{-- ================= Answers Section ================= --}}
                     <div class="col-md-12 mt-4">

                         {{-- ===== TRUE / FALSE ===== --}}
                         @if ($questionType === 'trueOrFalse')
                             <div class="col-md-6">
                                 <label>{{ __('Correct Answer') }}</label>

                                 <div class="d-flex gap-4 mt-2">
                                     <label>
                                         <input type="radio" value="true" wire:model="questionAnswers.0.correct">
                                         <i class="fa-solid fa-check text-success"></i>
                                     </label>

                                     <label>
                                         <input type="radio" value="false" wire:model="questionAnswers.0.correct">
                                         <i class="fa-solid fa-xmark text-danger"></i>
                                     </label>
                                 </div>
                             </div>
                         @endif

                         {{-- ===== CHOOSE ===== --}}
                         @if ($questionType === 'choose')

                             <div class="mb-3">
                                 <button class="btn btn-primary" wire:click.prevent="addQuestionAnswer">
                                     {{ __('Add New Answer') }}
                                 </button>
                             </div>

                             <div class="row">
                                 @foreach ($questionAnswers as $key => $answer)
                                     <div class="col-md-6">
                                         <div class="form-group">

                                             <label>
                                                 <i class="fa-solid fa-circle-dot"></i>
                                                 {{ __('الإختيار - ') }} {{ $key + 1 }}
                                             </label>

                                             <div class="d-flex gap-3 mt-2">

                                                 <input type="text" class="form-control"
                                                     wire:model="questionAnswers.{{ $key }}.title">

                                                 <label>
                                                     <input type="radio" name="correct_answer"
                                                         value="{{ $key }}" wire:model="correctAnswerIndex">
                                                     <i class="fa-solid fa-check text-success"></i>
                                                 </label>

                                             </div>
                                             <button type="button" class=" btn  border-0 text-danger"
                                                 wire:click.prevent="removeQuestionAnswer({{ $key }})">
                                                 <i class="fa-solid fa-xmark"></i>
                                             </button>
                                         </div>
                                     </div>
                                 @endforeach
                             </div>

                         @endif

                     </div>

                     {{-- ================= Submit ================= --}}
                     <div class="col-md-12 mt-4">
                         <button type="submit" class="btn btn-primary mt-3">
                             <i class="fa fa-{{ $update ? 'pencil' : 'save' }}"></i>
                             {{ $update ? __('update') : __('save') }}
                         </button>
                         &nbsp;&nbsp;
                         <button wire:click.prevent="newQuestion()" type="submit" class="btn btn-info mt-3"
                             {{ !$update ? 'hidden' : '' }}>
                             <i class="fa fa-plus"></i>
                         </button>
                         &nbsp;&nbsp;
                         <button
                             wire:click.prevent="back('show-teacher-quizs',['teacher_id_create_quiz'],['questionTitle','questionType','answerScore','isImage'
                         ,'questionAnswers','correctAnswerIndex','old_questionImage','quizQuestions'])"
                             class="btn btn-warning mt-3">
                             <i class="fa fa-arrow-left"></i>
                         </button>
                     </div>

                 </div>

             </form>
         </div>
         @php
             $i = 1;
         @endphp
         @foreach ($quiz->groupBy('type') as $type => $questions)
             {{-- العنوان --}}
             <h5 class="mt-4 mb-2 p-2 border-bottom">
                 {{ $i++ . '-' }} {{ $type == 'choose' ? 'ا ختر الإجابة الصحيحة' : 'صح أو خطأ' }}
                 {{ ':-' }}
             </h5>

             {{-- الأسئلة --}}
             <ol class="list-group list-group-numbered p-2">
                 @foreach ($questions as $question)
                     <li class="list-group-item d-flex justify-content-between align-items-start">
                         <div class="ms-2 me-auto">
                             <div class="fw-bold">{{ $question->text }}</div><br>
                             <button class="btn btn-success btn-sm"
                                 wire:click.prevent="editQuestion({{ $question->id }})">
                                 <i class="fa-regular fa-edit"></i>
                             </button>
                             &nbsp;&nbsp;
                             <button class="btn btn-danger btn-sm"
                                 onclick="confirmDelete({{ $question->id }} ,'deleteQuestion')">
                                 <i class="fa fa-trash"></i>
                             </button>
                         </div>
                         <span class="badge bg-primary rounded-pill">{{ __('Question Score') }} :
                             {{ $question->score }} </span>
                     </li>
                 @endforeach
             </ol>
         @endforeach
