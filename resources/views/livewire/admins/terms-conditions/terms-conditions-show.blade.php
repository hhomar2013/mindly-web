<div class="mt-5">
    <h4 class="mb-4 border-bottom pb-2">
        <i class="fa-solid fa-file-contract me-2"></i>سجل الشروط والأحكام (Terms)
    </h4>
    <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
        @foreach ($termsList as $terms)
        <div class="col">
            @include('livewire.term-card', ['term' => $terms])
        </div>
        @endforeach
    </div>

    <h4 class="mb-4 border-bottom pb-2">
        <i class="fa-solid fa-user-shield me-2"></i>سجل سياسات الخصوصية (Privacy)
    </h4>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($privacyList as $term)
        <div class="col">
            @include('livewire.term-card', ['term' => $term])
        </div>
        @endforeach
    </div>
</div>
