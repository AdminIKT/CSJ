<p class="small">{{ __('Showing :itemsX-:itemsY items from a total of :totalItems', ['itemsX' => $collection->firstItem()?:0, 'itemsY' => $collection->lastItem()?:0, 'totalItems' => $collection->total()]) }}</p>

<div class="table-responsive">
  <table class="table table-sm align-middle">
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
          <th class="small" scope="col">{{ __('Orders') }}
            <a class="{{ request()->get('sortBy') == 'supplier.orderCount' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.orderCount', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'supplier.orderCount' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.orderCount', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th class="small" scope="col">{{ __('Incidences') }}
            <a class="{{ request()->get('sortBy') == 'supplier.incidenceCount' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.incidenceCount', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'supplier.incidenceCount' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.incidenceCount', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
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
          <td>
            {{ $entity->getNif() }}</td>
          </td>
          <td>
            <span class="cbg me-1 {{ $entity->getStatusColor() }}" title="{{ $entity->getStatusName() }}"></span>
            <a href="{{ route('suppliers.show', ['supplier' => $entity->getId()]) }}" class="">{{ $entity->getName() }}</a>
          </td>
          <td>{{ $entity->getZip() }}</td>
          <td>
            {{ $entity->getCity() }}
            @if ($entity->getAddress())
            <span class="small text-muted">, {{ $entity->getAddress() }}</span>
            @endif
            @if ($entity->getRegion())
            <span class="small text-muted">({{ $entity->getRegion() }})</span>
            @endif
          </td>
          <td>{{ $entity->getOrderCount() }}</td>
          <td>{{ $entity->getIncidenceCount() }}</td>
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
                  @can('view', $entity)
                  <a href="{{ route('suppliers.show', ['supplier' => $entity->getId()]) }}" class="btn btn-light">
                    <i class="bx bxs-show"></i>
                  </a>
                  @endcan
                  @can('update', $entity)
                  <a href="{{ route('suppliers.edit', ['supplier' => $entity->getId()]) }}" class="btn btn-light">
                    <i class="bx bxs-pencil"></i>
                  </a>
                  @endcan
                  @can('delete', $entity)
                  {{ Form::button('<i class="bx bxs-trash-alt"></i>', [
                    'title'    => __('Delete'),
                    'class'    => 'btn btn-light', 
                    'onclick'  => "return confirm('".__('delete.confirm')."')",
                    'type'     => 'submit',
                  ]) }}
                  @endcan
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
