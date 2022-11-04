<div class="table-responsive">
    <p class="text-muted small my-1">{{ __('Showing :itemsX-:itemsY items from a total of :totalItems', ['itemsX' => $collection->firstItem()?:0, 'itemsY' => $collection->lastItem()?:0, 'totalItems' => $collection->total()]) }}</p>
  <table class="table table-sm">
    <thead>
        <tr>
            @if (!(isset($exclude) && in_array('orders', $exclude)))
            <th scope="col">{{ __('Order') }}</th>
            @endif
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
            <th scope="col">{{ __('Type') }}
                <a class="{{ request()->get('sortBy') == 'movement.type' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.type', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'movement.type' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.type', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th scope="col">{{ __('importe') }}
                <a class="{{ request()->get('sortBy') == 'movement.credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.credit', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'movement.credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.credit', 'sort' => 'desc']) }}">
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
        @php 
            $totalCredit = 0; 
            $trCredit    = 4;
        @endphp
        @foreach ($collection as $i => $entity)
        @php 
            if ($entity instanceof \App\Entities\Assignment) 
                $totalCredit += $entity->getCredit();
            else
                $totalCredit -= $entity->getCredit();
        @endphp
        <tr>
            @if (!(isset($exclude) && in_array('orders', $exclude)))
            <td class="align-middle">@if ($entity instanceof \App\Entities\InvoiceCharge) <a href="{{route('orders.show', ['order' => $entity->getOrder()->getId()])}}">{{ $entity->getOrder()->getSequence() }}</a> @else - @endif</td>
            @endif
            @if (!(isset($exclude) && in_array('accounts', $exclude)))
            <td class="align-middle"><a href="{{route('accounts.show', ['account' => $entity->getAccount()->getId()])}}" data-bs-toggle="tooltip" title="{{ $entity->getAccount()->getName() }}">{{ $entity->getAccount()->getSerial() }}</a></td>
            @endif
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            <td class="align-middle"><a href="{{route('areas.show', ['area' => $entity->getArea()->getId()])}}">{{ $entity->getArea()->getName() }}</a></td>
            @endif
            <td class="align-middle">{{ $entity->getTypeName() }}</td>
            <td class="align-middle">@if ($entity instanceof \App\Entities\Assignment)+@elseif ($entity instanceof \App\Entities\Charge)-@endif{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
            <td class="align-middle">{{ Str::limit($entity->getDetail(), 100, '...') }}</td>
            <td class="align-middle">{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        </tr>
        @endforeach
        @if ($pagination ?? '')
        <tr>
            <td class="text-center" colspan="{{ isset($exclude) ? 7 - count($exclude) : 7 }}">{{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}</td>
        </tr>
        @elseif ($collection->total())
        <tr style="background: #DDDDDD;">
            <td colspan="{{ $trCredit }}">{{ __('Total') }}:</td>
            <td>{{ number_format($totalCredit, 2, ",", ".") }}€</td>
            <td colspan="2"></td>
        </tr>
        @endif
    </tbody>
  </table>
</div>
