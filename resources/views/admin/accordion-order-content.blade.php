<p class="my-2 mt-0 text-center">
    <strong class="me-1">{{ __('Account') }}:</strong>
    <a href="{{ route('accounts.show', ['account' => $entity->getAccount()->getId()]) }}" title="{{ $entity->getAccount()->getName() }}">{{ $entity->getAccount()->getSerial() }}</a>
    <small class="text-muted">{{ $entity->getAccount()->getName() }}</small>
</p>

<div class="input-group input-group-sm justify-content-center">
    <span class="input-group-text">{{ __('Order') }}</span>
    <a href="{{ route('orders.show', ['order' => $entity->getId()]) }}" class="btn btn-outline-{{ \App\Entities\Order::statusBgColor($action->getAction()) }}">
        {{ $entity->getSequence() }}
    </a>
    @if ($entity->getEstimateFileId())
        <span class="input-group-text">{{ __('presupuesto') }}</span>
        <a href="{{ $entity->getEstimateFileUrl() }}" class="btn btn-outline-secondary" target="_blank" title="{{ __('Google storage estimate') }}">
            <img src="/img/google/drive-doc.png" alt="{{ __('Drive storage') }}" height="15px">
        </a>
    @endif
    @if ($entity->getInvoiceFileId())
        <span class="input-group-text">{{ __('Invoice') }}</span>
        <a href="{{ $entity->getInvoiceFileUrl() }}" class="btn btn-outline-secondary" target="_blank" title="{{ __('Google storage invoice') }}">
            <img src="{{ $entity->getInvoiceIcon() }}" alt="{{ __('Drive storage') }}" height="15px">
        </a>
    @endif
</div>
<p class="my-2 mb-0 text-center">
    <strong class="me-1">{{ __('Estimated credit') }}:</strong><span class="text-muted me-2">{{ $entity->getEstimatedCredit() ? number_format($entity->getEstimatedCredit(), 2, ",", ".").'€' : '-'}}</span>
    <strong class="me-1">{{ __('Credit') }}:</strong><span class="text-muted">{{ $entity->getCredit() ? number_format($entity->getCredit(), 2, ",", ".").'€' : '-'}}</span>
</p>
