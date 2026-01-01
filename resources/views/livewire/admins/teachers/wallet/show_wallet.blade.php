<div>
    <div class="row ">
        <div class="col-lg-12 ">
            <div class="card ">
                <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">


                    <h6 class="mb-2 card-title"> <i class="fa-solid fa-up-down"></i> {{ __('Transaction') }}</h6>

                    <div class="div text-end">
                        <button class="btn btn-danger btn-sm" wire:click.prevent="back()">
                            {{ __('Back') }}
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-items-center ">
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div>
                                                <i
                                                    class="fa-solid {{ $transaction->type == 'credit' ? 'fa-arrow-up text-success' : 'fa-arrow-down text-danger' }}"></i>
                                            </div>
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ __('Amount') }} </p>
                                                <h6 class="text-sm mb-0">{{ $transaction->amount }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ __('Source') }} </p>
                                                <h6 class="text-sm mb-0">{{ $transaction->source }}</h6>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="w-30">
                                        <div class="d-flex px-2 py-1 align-items-cente  r">
                                            <div class="ms-4">
                                                <p class="text-xs font-weight-bold mb-0">{{ __('Note') }} </p>
                                                <h6 class="text-sm mb-0">
                                                    {{ $transaction->notes == null || $transaction->notes == '' ? __('Note Not found') : $transaction->notes }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <div class="alert alert-danger">
                                            {{ __('No transactions found') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $transactions->links() }}
            </div>
        </div>

    </div>
</div>
