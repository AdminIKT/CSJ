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
        <th scope="col">{{ __('Area') }}</th>
        <th scope="col">{{ __('Type') }}</th>
        <th scope="col">{{ __('Products') }}</th>
        <th scope="col">{{ __('Estimated') }}
            <a class="{{ request()->get('sortBy') == 'estimatedCredit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'estimatedCredit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'estimatedCredit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'estimatedCredit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('Status') }}</th>
        <th scope="col">{{ __('Credit') }}
            <a class="{{ request()->get('sortBy') == 'credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'credit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'credit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('Detail') }}</th>
        <th scope="col">{{ __('Date') }}
            <a class="{{ request()->get('sortBy') == 'date' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'date', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'date' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'date', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('Actions') }}</th>
    </tr>
    </thead>
    <tbody> 
        @foreach ($collection as $i => $order)
        <tr>
            <td><a href="{{ route('orders.show', ['order' => $order->getId()]) }}">{{ $order->getSequence() }}</a></td>
            <td><a href="{{ route('areas.show', ['area' => $order->getArea()->getId()]) }}">{{ $order->getArea()->getName() }}-{{ $order->getArea()->getType() }}</a></td>
            <td>{{ $order->getArea()->getTypeName() }}</td>
            <td>{{ $order->getProducts()->count() }}</td>
            <td>{{ $order->getEstimatedCredit() }}€</td>
            <td>{{ $order->getStatusName() }}</td>
            <td>@if ($order->getCredit()) {{ $order->getCredit() }}€ @endif</td>
            <td>{{ $order->getDetail() }}</td>
            <td>{{ $order->getDate()->format("d/m/Y H:i") }}</td>
            <td>
            {{ Form::open([
                'route' => ['orders.destroy', $order->getId()], 
                'method' => 'delete',
            ]) }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('orders.show', ['order' => $order->getId()]) }}" class="btn btn-outline-secondary" title="{{ __('View') }}">
                        <span data-feather="eye"></span>
                    </a>
                    <a href="{{ route('orders.edit', ['order' => $order->getId()]) }}" class="btn btn-outline-secondary" title="{{ __('Edit') }}">
                        <span data-feather="edit-2"></span>
                    </a>
                    @if ($order->isStatus(\App\Entities\Order::STATUS_PAID))
                        <a href="" class="btn btn-outline-secondary" title="{{ __('Receive') }}">
                            <span data-feather="package"></span>
                        </a>
                    @endif
                    {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary' . ($order->isStatus(\App\Entities\Order::STATUS_PAID)?' disabled':''), 'type' => 'submit', 'title' => __('Delete')]) }}
                </div>
            {{ Form::close() }}
            </td>
        </tr>
        @endforeach
        @if ($pagination ?? '')
        <tr align="center">
            <td colspan="10">{{ $collection->links("pagination::bootstrap-4") }}</td>
        </tr>
        @endif
  </table>
</div>

