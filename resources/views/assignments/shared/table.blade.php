<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
      <thead>
      <tr>
          <th scope="col">{{ __('Area') }}</th>
          <th scope="col">{{ __('Type') }}</th>
          <th scope="col">{{ __('importe') }}
            <a class="{{ request()->get('sortBy') == 'credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'credit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'credit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
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
          <td><a href="{{ route('areas.show', ['area' => $entity->getArea()->getId()]) }}">{{ $entity->getArea()->getName() }}-{{ $entity->getArea()->getType() }}</a></td>
          <td>{{ $entity->getTypeName() }}</td>
          <td>{{ number_format($entity->getCredit(), 2, ",", ".") }} €</td>
          <td>{{ $entity->getDetail() }}</td>
          <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
          <td>
          {{ Form::open([
              'route' => ['assignments.destroy', $entity->getId()], 
              'method' => 'delete',
          ]) }}
              <div class="btn-group btn-group-sm" role="group">
                  <!--<a href="{{ route('assignments.show', ['assignment' => $entity->getId()]) }}" class="btn btn-outline-secondary">
                    <span data-feather="eye"></span>
                  </a>
                  <a href="{{ route('assignments.edit', ['assignment' => $entity->getId()]) }}" class="btn btn-outline-secondary">
                    <span data-feather="edit-2"></span>
                  </a>-->
                  {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit']) }}
              </div>
          {{ Form::close() }}
          </td>
      </tr>
      @endforeach
      </tbody>
    </table> 
    <div class="col-md-12 text-center">{{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}</div>
</div>
