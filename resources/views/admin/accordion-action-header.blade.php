<div class="d-flex w-100 justify-content-between align-items-center">
    <div class="accordion-info">
        <span>{{ $entity->getTypeName() }}</span>
        @if ($entity->getType() === \App\Entities\Action\OrderAction::TYPE_STATUS)
            <span class="cbg bg-{{ \App\Entities\Order::statusBgColor($entity->getAction()) }} mx-1"></span>
            <small class="text-muted">
                {{ \App\Entities\Order::statusName($entity->getAction()) }}
            </small>
        @endif
        @include('admin.accordion-user-header', ['entity' => $action])
    </div>
    <h6 class="card-subtitle text-nowrap mx-4" data-bs-toggle="tooltip" title="{{ $entity->getAccount() }}">{{ $entity->getAccount()->getSerial() }}</h6>
</div>

