<div class="table-responsive">
    <p class="text-muted small my-1">{{ __('Showing :itemsX-:itemsY items from a total of :totalItems', ['itemsX' => $collection->firstItem()?:0, 'itemsY' => $collection->lastItem()?:0, 'totalItems' => $collection->total()]) }}</p>
  <table class="table table-sm">
    <thead>
        <tr>
            @if (!(isset($exclude) && in_array('orders', $exclude)))
            <th class="small" scope="col" colspan="3">
                {{ __('Order') }}
            </th>
            @endif
            @if (!(isset($exclude) && in_array('suppliers', $exclude)))
            <th class="small" scope="col">{{ __('Supplier') }}</th>
            @endif
            @if (!(isset($exclude) && in_array('accounts', $exclude)))
            <th class="small" scope="col">{{ __('Account') }}
                <a class="{{ request()->get('sortBy') == 'account.acronym' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.acronym', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'account.acronym' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.acronym', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            @endif
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            <th class="small" scope="col">{{ __('Area') }}
                <a class="{{ request()->get('sortBy') == 'area.name' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'area.name', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'area.name' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'area.name', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            @endif
            <th class="small" scope="col">{{ __('Type') }}
                <a class="{{ request()->get('sortBy') == 'movement.type' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.type', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'movement.type' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.type', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th class="small" scope="col">{{ __('importe') }}
                <a class="{{ request()->get('sortBy') == 'movement.credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.credit', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'movement.credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'movement.credit', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th class="small" scope="col">{{ __('Detail') }}</th>
            <th class="small" scope="col">{{ __('Created') }}
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
            $trCredit    = 7;
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
                @if ($entity instanceof \App\Entities\OrderCharge) 
                    <td>
                       <a href="{{route('orders.show', ['order' => $entity->getOrder()->getId()])}}">{{ $entity->getOrder()->getSequence() }}</a> 
                    </td>
                    <td>
                       @if ($entity->getOrder()->getEstimated())
                       <a href='{{ asset("storage/{$entity->getOrder()->getEstimated()}") }}' target="_blank" title="{{__('Local storage')}}">
                           <i class="bx bx-xs bx-hdd"></i>
                       </a>
                       @endif
                    </td>
                    <td>
                       @if ($entity->getOrder()->getEstimatedFileId())
                       <a href="{{ $entity->getOrder()->getEstimatedFileUrl() }}" target="_blank" title="{{ __('Google storage') }}">
                           <img src="/img/google/drive.png" alt="{{ __('Drive storage') }}" width="16px">
                       </a>
                       @endif
                    </td>
                @else
                    <td scope="col" colspan="3">
                @endif
            @endif
            @if (!($exclude && in_array('suppliers', $exclude)))
                <td>
                    @if ($entity instanceof \App\Entities\OrderCharge) 
                   <a href="{{route('suppliers.show', ['supplier' => $entity->getOrder()->getSupplier()->getId()])}}" class="small text-muted">{{ $entity->getOrder()->getSupplier()->getName() }}</a> 
                    @endif
                </td>
            @endif
            @if (!(isset($exclude) && in_array('accounts', $exclude)))
            <td><a href="{{route('accounts.show', ['account' => $entity->getAccount()->getId()])}}" data-bs-toggle="tooltip" title="{{ $entity->getAccount()->getName() }}">{{ $entity->getAccount()->getSerial() }}</a></td>
            @endif
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            <td><a href="{{route('areas.show', ['area' => $entity->getArea()->getId()])}}">{{ $entity->getArea()->getName() }}</a></td>
            @endif
            <td>
                @if ($entity instanceof \App\Entities\Charge) 
                    <span class="me-1 cbg bg-danger"></span>
                @elseif ($entity instanceof \App\Entities\Assignment) 
                    <span class="me-1 cbg bg-success"></span>
                @endif
                {{ $entity->getTypeName() }}
            </td>
            <td>@if ($entity instanceof \App\Entities\Assignment)+@elseif ($entity instanceof \App\Entities\Charge)-@endif{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
            <td>{{ Str::limit($entity->getDetail(), 35, '...') }}</td>
            <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        </tr>
        @endforeach
        @if ($pagination ?? '')
        <tr>
            <td class="text-center" colspan="{{ isset($exclude) ? 10 - (count($exclude)) : 10 }}">{{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}</td>
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
