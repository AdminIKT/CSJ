<div class="table-responsive {!! $classes ?? 'bg-white px-2 mb-2 rounded rounded-5' !!}">
    <p class="text-muted small my-1">{{ __('Showing :itemsX-:itemsY items from a total of :totalItems', ['itemsX' => $collection->firstItem()?:0, 'itemsY' => $collection->lastItem()?:0, 'totalItems' => $collection->total()]) }}</p>
    <table class="table table-sm align-middle">
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
                <!--<td>{{ Carbon\Carbon::parse($action->getCreated())->diffForHumans() }}</td>-->
                <td>{{ $action->getCreated()->format(\DateTimeInterface::RFC7231) }}</td>
            </tr>
            @endforeach
            @if ($pagination ?? '')
                <tr>
                    <td colspan="5">
                        {{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
