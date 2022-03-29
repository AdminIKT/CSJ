<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
        <tr>
            <th scope="col">{{ __('Order') }}</th>
            <th scope="col">{{ __('Area') }}</th>
            <th scope="col">{{ __('Credit') }}</th>
            <th scope="col">{{ __('Invoice') }}</th>
            <th scope="col">{{ __('Type') }}</th>
            <th scope="col">{{ __('Detail') }}</th>
            <th scope="col">{{ __('Created') }}</th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collection as $i => $entity)
        <tr>
            <td><a href="{{route('orders.show', ['order' => $entity->getOrder()->getId()])}}">{{ $entity->getOrder()->getSequence() }}</a></td>
            <td><a href="{{route('areas.show', ['area' => $entity->getArea()->getId()])}}">{{ $entity->getArea()->getName() }}-{{ $entity->getArea()->getType() }}</a></td>
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
        <tr align="center">
            <td colspan="8">{{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}</td>
        </tr>
        @endif
    </tbody>
  </table>
</div>
