<p class="small">{{ __('Showing :itemsX-:itemsY items from a total of :totalItems', ['itemsX' => $collection->firstItem()?:0, 'itemsY' => $collection->lastItem()?:0, 'totalItems' => $collection->total()]) }}</p>

<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
      <thead>
      <tr>
          <th scope="col">{{ __('NIF') }}
            <a class="{{ request()->get('sortBy') == 'supplier.nif' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.nif', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'supplier.nif' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.nif', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th scope="col">{{ __('Name') }}
            <a class="{{ request()->get('sortBy') == 'supplier.name' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.name', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'supplier.name' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.name', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th scope="col">{{ __('City') }}
            <a class="{{ request()->get('sortBy') == 'supplier.city' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.city', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'supplier.city' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.city', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th scope="col">{{ __('Zip') }}</th>
          <th scope="col">{{ __('Address') }}</th>
          <th scope="col">{{ __('Recommendable') }}</th>
          <th scope="col">{{ __('Acceptable') }}</th>
          @if (!(isset($exclude) && in_array('users', $exclude)))
          <th scope="col">{{ __('User') }}</th>
          @endif
          @if (!(isset($exclude) && in_array('created', $exclude)))
          <th scope="col">{{ __('Created') }}
            <a class="{{ request()->get('sortBy') == 'supplier.created' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.created', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'supplier.created' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'supplier.created', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          @endif
          @if (!(isset($exclude) && in_array('actions', $exclude)))
          <th scope="col">{{ __('Actions') }}</th>
          @endif
      </tr>
      </thead>
      <tbody>
      @foreach ($collection as $i => $entity)
      <tr>
          <td>{{ $entity->getNif() }}</td>
          <td>{{ $entity->getName() }}</td>
          <td>{{ $entity->getCity() }}</td>
          <td>{{ $entity->getZip() }}</td>
          <td>{{ $entity->getAddress() }}</td>
          <td>{{ $entity->getRecommendable() ? __('Yes') : __('No') }}</td>
          <td>{{ $entity->getAcceptable() ? __('Yes') : __('No') }}</td>
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
          <td colspan="10">{{ $collection->links("pagination::bootstrap-4") }}</td>
      </tr>
      @endif
      </tbody>
    </table> 
</div>