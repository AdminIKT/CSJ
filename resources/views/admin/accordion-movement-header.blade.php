<div class="d-flex w-100 justify-content-between align-items-center">
    <div class="accordion-info">
        {{ $entity->getTypeName() }}
        <div class="small mt-1">
            <span class="bg-light text-dark rounded p-1">{{ number_format($entity->getCredit(), 2, ",", ".") }}€</span>
            <span data-bs-toggle="tooltip" class="small text-muted" title="{{ Carbon\Carbon::parse($entity->getCreated())->translatedFormat('m/d/Y h:i a') }}">
                {{ Carbon\Carbon::parse($entity->getCreated())->diffForHumans() }}
            </span>
        </div>
    </div>
    <h6 class="card-subtitle text-nowrap mx-4" data-bs-toggle="tooltip" title="{{ $entity->getAccount() }}">{{ $entity->getAccount()->getSerial() }}</h6>
</div>
