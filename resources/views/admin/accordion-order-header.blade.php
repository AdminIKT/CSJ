<div class="d-flex w-100 justify-content-between align-items-center">
    <div class="accordion-info">
        <span>{{ $entity->getSequence() }}</span>
        <span class="cbg bg-{{ \App\Entities\Order::statusBgColor($entity->getStatus()) }} mx-1"></span>
        <small class="text-muted">
            {{ \App\Entities\Order::statusName($entity->getStatus()) }}
        </small>
        @include('admin.accordion-user-header', ['entity' => $entity])
    </div>
    <h6 class="card-subtitle text-nowrap mx-4" data-bs-toggle="tooltip" title="{{ $entity->getAccount() }}">{{ $entity->getAccount()->getSerial() }}</h6>
</div>

