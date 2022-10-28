<div class="table-responsive">
    <table class="table table-hover table-sm">
        <thead>
        <tr>
            @if (!(isset($exclude) && in_array('orders', $exclude)))
            <th>{{ __('Order') }} nº</th>
            @endif
            <th>{{ __('Type') }}</th>
            <th>{{ __('Action') }}</th>
            <th>{{ __('User') }}</th>
            <th>{{ __('Created') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($collection as $action)
        <tr>
            @if (!(isset($exclude) && in_array('orders', $exclude)))
            <td>@if ($action->getEntity())<a href="{{ route('orders.show', ['order' => $action->getEntity()->getId()]) }}">{{ $action->getEntity()->getSequence() }}</a>@endif</td>
            @endif
            <td>{{ $action->getTypeName() }}</td>
            <td><span class="badge {{ \App\Entities\Order::statusColor($action->getAction()) }}">{{ \App\Entities\Order::statusName($action->getAction()) }}</span></td>
            <td>{{ $action->getUser()->getName() }}</td>
            <td>{{ $action->getCreated()->format("d/m/Y H:i") }}</td>
        </tr>
        @endforeach
        <tbody>
    </table>
    @if ($pagination ?? '')
        {{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}
    @endif
</div>
