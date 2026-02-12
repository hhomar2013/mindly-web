   @if ($governorate_selected)
       @include('livewire.admins.cities.table')

   @else
       <div class="row p-4">
           @foreach ($governores as $gov)
               <div class="col-lg-2 h6">
                   <a class="btn btn-primary w-100"
                       wire:click="getGovernorate({{ $gov->id }},'{{ $gov->name }}')">{{ $gov->name }}</a>
               </div>
           @endforeach
       </div>
   @endif
