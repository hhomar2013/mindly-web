   <div class="row p-3 border-start border-3 border-success mb-3">
       <h3 class="border-bottom pb-2">{{ __('Ads Configuration') }}</h3>
       <div class="col-lg-6 mb-3">
           <label>{{ __('Start Date') }}</label>
           <input type="date" class="form-control shadow-sm" wire:model="start_date">
           @error('start_date')
               <span class="text-danger small">{{ $message }}</span>
           @enderror
       </div>
       <div class="col-lg-6 mb-3">
           <label>{{ __('End Date') }}</label>
           <input type="date" class="form-control shadow-sm" wire:model="end_date">
           @error('end_date')
               <span class="text-danger small">{{ $message }}</span>
           @enderror
       </div>

       <div class="col-lg-12 mb-3">
           <label>{{ __('Ads Comment') }}</label>
           <textarea class="form-control shadow-sm" wire:model="comment" rows="2"></textarea>
       </div>

   </div>
