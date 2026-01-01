<div class="card-header text-strat">
    <button class="btn btn-success" wire:click="createCL">
        <i class="fa fa-plus"></i>
        {{ __('Add Code List') }}</button>
</div>
<div class="card-body">
    <div class="row">
        @forelse ($codeList as $codeListVal)
            <div class="col-lg-4">
                <div class="card bg-gradient-success">
                    <div class="card-body text-center">
                        <h5 class="card-title text-white">{{ $codeListVal->teacherCourseOverview->name }}</h6>
                            <h5 class="card-title text-white">{{ $codeListVal->teacherCourseOverview->teacher->name }}
                                </h6>
                                <h6 class="card-title text-white">{{ __('Count of codes') }}
                                    {{ $codeListVal->code_count }}</h6>

                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <button class="btn btn-warning btn-sm rounded-pill" wire:click="showCodes({{ $codeListVal->id }})"><i
                                    class="fa-solid fa-eye"></i></button>
                            <button class="btn btn-primary btn-sm rounded-pill" wire:click="edit({{ $codeListVal->id }})"><i
                                    class="fa-solid fa-edit"></i></button>
                            <button class="btn btn-danger btn-sm rounded-pill"
                                onclick="confirmDelete({{ $codeListVal->id }} ,'deleteCodeList')"><i
                                    class="fa-solid fa-trash"></i></button>
                            @livewire('switcher', ['model' => $codeListVal, 'field' => 'status'], key($codeListVal->id))
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-lg-12">
                <div class="card bg-danger">
                    <div class="card-body  text-center text-white">
                        <h5 class="card-title">{{ __('No Content Types Found') }}</h5>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

</div>
