<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
    <tr>
        <th scope="col">{{ __('Order') }} nº
            <a class="{{ request()->get('sortBy') == 'sequence' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'sequence', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'sequence' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'sequence', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        @if (!(isset($exclude) && in_array('areas', $exclude)))
        <th scope="col">{{ __('Area') }}</th>
        @endif
        @if (!(isset($exclude) && in_array('suppliers', $exclude)))
        <th scope="col">{{ __('Supplier') }}</th>
        @endif
        <th scope="col">{{ __('Type') }}</th>
        <!--<th scope="col">{{ __('Products') }}</th>-->
        <th scope="col">{{ __('Estimated') }}
            <a class="{{ request()->get('sortBy') == 'estimatedCredit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'estimatedCredit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'estimatedCredit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'estimatedCredit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col"></th>
        <th scope="col">{{ __('Status') }}</th>
        <th scope="col">{{ __('Credit') }}
            <a class="{{ request()->get('sortBy') == 'credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'credit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'credit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('Date') }}
            <a class="{{ request()->get('sortBy') == 'date' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'date', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'date' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'date', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('User') }}</th>
        <th scope="col">{{ __('Actions') }}</th>
    </tr>
    </thead>
    <tbody> 
        @foreach ($collection as $i => $order)
        <tr>
            <td><a href="{{ route('orders.show', ['order' => $order->getId()]) }}">{{ $order->getSequence() }}</a></td>
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            <td><a href="{{ route('areas.show', ['area' => $order->getArea()->getId()]) }}">{{ $order->getArea()->getName() }}-{{ $order->getArea()->getType() }}</a></td>
            @endif
            @if (!(isset($exclude) && in_array('suppliers', $exclude)))
            <td><a href="{{ route('suppliers.show', ['supplier' => $order->getSupplier()->getId()]) }}">{{ $order->getSupplier()->getName() }}</a></td>
            @endif
            <td>{{ $order->getArea()->getTypeName() }}</td>
            <!--<td>{{ $order->getProducts()->count() }}</td>-->
            <td>{{ number_format($order->getEstimatedCredit(), 2, ",", ".") }}€</td>
            <td>@if ($order->getEstimated())<a href='{{ asset("storage/{$order->getEstimated()}") }}' target="_blank">{{ $order->getEstimated() }}</a>@else-@endif</td>
            <td>{{ $order->getStatusName() }}</td>
            <td>@if ($order->getCredit()) {{ number_format($order->getCredit(), 2, ",", ".") }}€ @endif</td>
            <td>{{ $order->getDate()->format("d/m/Y H:i") }}</td>
            <td>{{ $order->getUser()->getShort() }}</td>
            <td>
            {{ Form::open([
                'route' => ['orders.destroy', $order->getId()], 
                'method' => 'delete',
            ]) }}
                <div class="btn-group btn-group-sm" role="group">
                @can('show-order', $order)
                    <a href="{{ route('orders.show', ['order' => $order->getId()]) }}" class="btn btn-outline-secondary" title="{{ __('View') }}">
                        <span data-feather="eye"></span>
                    </a>
                @endcan
                @can('update-order', $order)
                    <a href="{{ route('orders.edit', ['order' => $order->getId()]) }}" class="btn btn-outline-secondary" title="{{ __('Edit') }}">
                        <span data-feather="edit-2"></span>
                    </a>
                @endcan
                @if ($order->isPaid())
                    <a href="" class="btn btn-outline-secondary" title="{{ __('Receive') }}">
                        <span data-feather="package"></span>
                    </a>
                @endif
                @can('delete-order', $order)
                    {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary' . ($order->isPaid() ? ' disabled':''), 'type' => 'submit', 'title' => __('Delete')]) }}
                @endcan
                </div>
            {{ Form::close() }}
            </td>
        </tr>
        @endforeach
        @if ($pagination ?? '')
        <tr>
            <td class="text-center" colspan="{{ isset($exclude) ? 11 - count($exclude) : 11 }}">{{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}</td>
        </tr>
        @endif
  </table>
</div>

