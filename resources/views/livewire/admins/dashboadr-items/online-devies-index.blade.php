<div>
    <div class="row mt-4">

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">{{ __('Online Now') }}</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        @forelse ($studentLogs as $studentLog)
                            <li
                                class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                        <i class="ni ni-mobile-button text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">{{ $studentLog->student->name }}</h6>
                                        <span class="text-xs">{{ $studentLog->updated_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <button
                                        class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i
                                            class="ni ni-bold-right" aria-hidden="true"></i></button>
                                </div>
                            </li>
                        @empty
                            <li>
                                <div class="d-flex align-items-center">
                                    <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                                        <i class="ni ni-single-02 text-white opacity-10"></i>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm">No active users</h6>
                                        <span class="text-xs">Currently no users online</span>
                                    </div>
                                </div>
                            </li>
                        @endforelse


                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
