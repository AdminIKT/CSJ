<p class="small">{{ __('Showing :itemsX-:itemsY items from a total of :totalItems', ['itemsX' => $collection->firstItem()?:0, 'itemsY' => $collection->lastItem()?:0, 'totalItems' => $collection->total()]) }}</p>

<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
      <thead>
      <tr>
          <th class="small" scope="col">{{ __('NIF') }}
            <a class="{{ request()->get('sortBy') == 'supplier.nif' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.nif', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'supplier.nif' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.nif', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th class="small" scope="col">{{ __('Name') }}
            <a class="{{ request()->get('sortBy') == 'supplier.name' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.name', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'supplier.name' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.name', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th class="small" scope="col"> {{ __('Zip') }}
            <a class="{{ request()->get('sortBy') == 'supplier.zip' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.zip', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'supplier.zip' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.zip', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th class="small" scope="col">{{ __('City') }}
            <a class="{{ request()->get('sortBy') == 'supplier.city' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.city', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'supplier.city' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.city', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th class="small" scope="col">{{ __('Address') }}</th>
          <th class="small" scope="col">{{ __('Status') }}</th>
          <th class="small" scope="col"></th>
          <th class="small" scope="col"></th>
          <th class="small" scope="col">{{ __('Predicted') }}
            <a class="{{ request()->get('sortBy') == 'invoiced.estimated' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'invoiced.estimated', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'invoiced.estimated' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'invoiced.estimated', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th class="small" scope="col">{{ __('Invoiced') }}
            <a class="{{ request()->get('sortBy') == 'invoiced.credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'invoiced.credit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'invoiced.credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'invoiced.credit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          @if (!(isset($exclude) && in_array('users', $exclude)))
          <th class="small" scope="col">{{ __('User') }}</th>
          @endif
          @if (!(isset($exclude) && in_array('created', $exclude)))
          <th class="small" scope="col">{{ __('Created') }}
            <a class="{{ request()->get('sortBy') == 'supplier.created' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.created', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'supplier.created' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.created', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          @endif
          @if (!(isset($exclude) && in_array('actions', $exclude)))
          <th class="small" scope="col">{{ __('Actions') }}</th>
          @endif
      </tr>
      </thead>
      <tbody>
      @foreach ($collection as $i => $entity)
      <tr>
          <td>{{ $entity->getNif() }}</td>
          <td>
            <a href="{{ route('suppliers.show', ['supplier' => $entity->getId()]) }}">{{ $entity->getName() }}</a>
          </td>
          <td>{{ $entity->getZip() }}</td>
          <td>
            {{ $entity->getCity() }}
            @if ($entity->getRegion())
            <span class="small text-muted">({{ $entity->getRegion() }})</span>
            @endif
          </td>
          <td>{{ $entity->getAddress() }}</td>
          <td>
            <span class="badge {{ $entity->isValidated() ? 'bg-success' : 'bg-danger' }}">{{ $entity->getStatusName() }}</span>
          </td>
          <td>@if ($entity->getAcceptable()) <span class="badge bg-success">{{ __('Acceptable') }}</span> @endif</td>
          <td>@if ($entity->getRecommendable()) <span class="badge bg-success">{{ __('Recommendable') }}</span> @endif</td>
          <td>
            @if (null !== ($inv = $entity->getInvoiced(date('Y'))))
            {{ number_format($inv->getEstimated(), 2, ",", ".")}} €
            @endif
          </td>
          <td>
            @if (null !== ($inv = $entity->getInvoiced(date('Y'))))
            {{ number_format($inv->getCredit(), 2, ",", ".")}} €
            @endif
          </td>
          @if (!(isset($exclude) && in_array('users', $exclude)))
          <td>{{ $entity->getUser()->getShort() }}</td>
          @endif
          @if (!(isset($exclude) && in_array('created', $exclude)))
          <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
          @endif
          @if (!(isset($exclude) && in_array('actions', $exclude)))
          <td>
          {{ Form::open([
              'route' => ['suppliers.destroy', $entity->getId()], 
              'method' => 'delete',
          ]) }}
              <div class="btn-group btn-group-sm" role="group">
                  <a href="{{ route('suppliers.show', ['supplier' => $entity->getId()]) }}" class="btn btn-outline-secondary">
                    <span data-feather="eye"></span>
                  </a>
                  <a href="{{ route('suppliers.edit', ['supplier' => $entity->getId()]) }}" class="btn btn-outline-secondary">
                    <span data-feather="edit-2"></span>
                  </a>
                  {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'disabled' => $entity->getOrders()->count() ? true : false]) }}
              </div>
          {{ Form::close() }}
          </td>
          @endif
      </tr>
      @endforeach
      @if ($pagination ?? '')
      <tr align="center">
          <td colspan="13">{{ $collection->links("pagination::bootstrap-4") }}</td>
      </tr>
      @endif
      </tbody>
    </table> 
</div>
