<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
        <tr>
            @if (!(isset($exclude) && in_array('accounts', $exclude)))
            <th scope="col">{{ __('Account') }}</th>
            @endif
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            <th scope="col">{{ __('Area') }}</th>
            @endif
            <th scope="col">{{ __('importe') }}
                <a class="{{ request()->get('sortBy') == 'movement.credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.credit', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'movement.credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.credit', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th scope="col">{{ __('Type') }}</th>
            <th scope="col">{{ __('Detail') }}</th>
            <th scope="col">{{ __('Created') }}
                <a class="{{ request()->get('sortBy') == 'movement.created' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.created', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'movement.created' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.created', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($collection as $i => $entity)
        <tr>
            @if (!(isset($exclude) && in_array('accounts', $exclude)))
            <td><a href="{{route('accounts.show', ['account' => $entity->getAccount()->getId()])}}">{{ $entity->getAccount()->getSerial() }}</a></td>
            @endif
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            <td><a href="{{route('areas.show', ['area' => $entity->getArea()->getId()])}}">{{ $entity->getArea()->getName() }}</a></td>
            @endif
            <td>@if ($entity instanceof \App\Entities\Assignment) + @elseif ($entity instanceof \App\Entities\Charge) - @endif {{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
            <td>{{ $entity->getTypeName() }}</td>
            <td>{{ $entity->getDetail() }}</td>
            <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        </tr>
        @endforeach
        @if ($pagination ?? '')
        <tr>
            <td class="text-center" colspan="{{ isset($exclude) ? 6 - count($exclude) : 6 }}">{{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}</td>
        </tr>
        @endif
    </tbody>
  </table>
</div>
