<div class="table-responsive">
    <table class="table table-hover table-sm">
        <thead>
        <tr>
            @if (!(isset($exclude) && in_array('entity', $exclude)))
            <th>{{ __('Entity') }}</th>
            @endif
            <th>{{ __('Type') }}</th>
            <th>{{ __('Action') }}</th>
            <th>{{ __('User') }}</th>
            @if (!(isset($exclude) && in_array('created', $exclude)))
            <th scope="col">{{ __('Created') }}
              <a class="{{ request()->get('sortBy') == 'action.created' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'action.created', 'sort' => 'asc']) }}">
                  <span data-feather="chevron-up"></span>
              </a>
              <a class="{{ request()->get('sortBy') == 'action.created' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'action.created', 'sort' => 'desc']) }}">
                  <span data-feather="chevron-down"></span>
              </a>
            </th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach ($collection as $action)
        <tr>
            @if (!(isset($exclude) && in_array('entity', $exclude)))
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
