<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
        <tr>
            @if (!(isset($exclude) && in_array('orders', $exclude)))
            <th scope="col">{{ __('Order') }} nº
                <a class="{{ request()->get('sortBy') == 'sequence' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'sequence', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'sequence' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'sequence', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            @endif
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            <th scope="col">{{ __('Area') }}</th>
            @endif
            @if (!(isset($exclude) && in_array('suppliers', $exclude)))
            <th scope="col">{{ __('Supplier') }}</th>
            @endif
            <th scope="col">{{ __('importe') }}
                <a class="{{ request()->get('sortBy') == 'credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'credit', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'credit', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th scope="col">{{ __('Invoice') }}
                <a class="{{ request()->get('sortBy') == 'invoice' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'invoice', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'invoice' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'invoice', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th scope="col">{{ __('Type') }}</th>
            <th scope="col">{{ __('Detail') }}</th>
            <th scope="col">{{ __('Created') }}
                <a class="{{ request()->get('sortBy') == 'created' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'created', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'created' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'created', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collection as $i => $entity)
        <tr>
            @if (!(isset($exclude) && in_array('orders', $exclude)))
            <td><a href="{{route('orders.show', ['order' => $entity->getOrder()->getId()])}}">{{ $entity->getOrder()->getSequence() }}</a></td>
            @endif
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            <td><a href="{{route('areas.show', ['area' => $entity->getArea()->getId()])}}">{{ $entity->getArea()->getName() }}-{{ $entity->getArea()->getType() }}</a></td>
            @endif
            @if (!(isset($exclude) && in_array('suppliers', $exclude)))
            <td><a href="{{ route('suppliers.show', ['supplier' => $entity->getOrder()->getSupplier()->getId()]) }}">{{ $entity->getOrder()->getSupplier()->getName() }}</a></td>
            @endif
            <td>{{ $entity->getCredit() }}€</td>
            <td>{{ $entity->getInvoice() }}</td>
            <td>{{ $entity->getTypeName() }}</td>
            <td>{{ $entity->getDetail() }}</td>
            <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
            <td>
            {{ Form::open([
                'route' => ['movements.destroy', $entity->getId()], 
                'method' => 'delete',
            ]) }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('movements.show', ['movement' => $entity->getId()]) }}" class="btn btn-outline-secondary disabled" title="{{ __('View') }}">
                        <span data-feather="eye"></span>
                    </a>
                    <a href="{{ route('movements.edit', ['movement' => $entity->getId()]) }}" class="btn btn-outline-secondary disabled">
                        <span data-feather="edit-2"></span>
                    </a>
                    {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary disabled', 'type' => 'submit', 'title' => __('Delete')]) }}
                </div>
            {{ Form::close() }}
            </td>
        </tr>
        @endforeach
        @if ($pagination ?? '')
        <tr>
            <td class="text-center" colspan="{{ isset($exclude) ? 9 - count($exclude) : 9 }}">{{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}</td>
        </tr>
        @endif
    </tbody>
  </table>
</div>
