<p class="small">{{ __('Showing :itemsX-:itemsY items from a total of :totalItems', ['itemsX' => $collection->firstItem()?:0, 'itemsY' => $collection->lastItem()?:0, 'totalItems' => $collection->total()]) }}</p>

<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
        <tr>
            @if (!(isset($exclude) && in_array('accounts', $exclude)))
            <th scope="col">{{ __('Account') }}
                <a class="{{ request()->get('sortBy') == 'account.acronym' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.acronym', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'account.acronym' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.acronym', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            @endif
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            <th scope="col">{{ __('Area') }}
                <a class="{{ request()->get('sortBy') == 'area.name' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'area.name', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'area.name' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'area.name', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            @endif
            <th scope="col">{{ __('Order') }}</th>
            <th scope="col">{{ __('importe') }}
                <a class="{{ request()->get('sortBy') == 'movement.credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.credit', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'movement.credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.credit', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th scope="col">{{ __('Type') }}
                <a class="{{ request()->get('sortBy') == 'movement.type' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.type', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'movement.type' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.type', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
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
            <td class="align-middle"><a href="{{route('accounts.show', ['account' => $entity->getAccount()->getId()])}}" title="{{ $entity->getAccount()->getName() }}">{{ $entity->getAccount()->getSerial() }}</a></td>
            @endif
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            <td class="align-middle"><a href="{{route('areas.show', ['area' => $entity->getArea()->getId()])}}">{{ $entity->getArea()->getName() }}</a></td>
            @endif
            <td class="align-middle">@if ($entity instanceof \App\Entities\InvoiceCharge) <a href="{{route('orders.show', ['order' => $entity->getOrder()->getId()])}}">{{ $entity->getOrder()->getSequence() }}</a> @else - @endif</td>
            <td class="align-middle">@if ($entity instanceof \App\Entities\Assignment)+@elseif ($entity instanceof \App\Entities\Charge)-@endif{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
            <td class="align-middle">{{ $entity->getTypeName() }}</td>
            <td class="align-middle">{{ $entity->getDetail() }}</td>
            <td class="align-middle">{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
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
