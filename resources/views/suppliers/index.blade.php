@extends('new_layout')
@section('title'){{ __('Suppliers') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('suppliers.create') }}" class="btn btn-sm btn-outline-secondary">
    <span data-feather="plus"></span> {{ __('New') }}
    </a>
@endsection
@section('content')
<form action="{{ route('suppliers.index') }}" method="GET" class="row mb-3">
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('Nif') }}</span>
          {{ Form::text('nif', request()->input('nif'), ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('Name') }}</span>
          {{ Form::text('name', request()->input('name'), ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('City') }}</span>
          </select>
          {{ Form::select('city', [null => __('selecciona')] + $cities, request()->input('city'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('Recommendable') }}</span>
          {{ Form::select('recommendable', [
              null  => __('selecciona'),
              true  => __('Yes'),
              false => __('No'),
          ], request()->input('recommendable'), ['class'=>'form-select']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('Acceptable') }}</span>
          {{ Form::select('acceptable', [
              null  => __('selecciona'),
              true  => __('Yes'),
              false => __('No'),
          ], request()->input('acceptable'), ['class'=>'form-select']) }}
          <button class="btn btn-primary" type="submit" id="button-addon2">
            <span data-feather="search"></span>
          </button>
        </div>
    </div>
</form>

<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
      <thead>
      <tr>
          <th scope="col">{{ __('NIF') }}
            <a class="{{ request()->get('sortBy') == 'nif' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'nif', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'nif' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'nif', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th scope="col">{{ __('Name') }}
            <a class="{{ request()->get('sortBy') == 'name' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'name', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'name' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'name', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th scope="col">{{ __('City') }}
            <a class="{{ request()->get('sortBy') == 'city' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'city', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'city' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'city', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
          </th>
          <th scope="col">{{ __('Zip') }}</th>
          <th scope="col">{{ __('Address') }}</th>
          <th scope="col">{{ __('Recommendable') }}</th>
          <th scope="col">{{ __('Acceptable') }}</th>
          <th scope="col">{{ __('User') }}</th>
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
          <td>{{ $entity->getNif() }}</td>
          <td>{{ $entity->getName() }}</td>
          <td>{{ $entity->getCity() }}</td>
          <td>{{ $entity->getZip() }}</td>
          <td>{{ $entity->getAddress() }}</td>
          <td>{{ $entity->getRecommendable() ? __('Yes') : __('No') }}</td>
          <td>{{ $entity->getAcceptable() ? __('Yes') : __('No') }}</td>
          <td>{{ $entity->getUser()->getShort() }}</td>
          <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
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
      </tr>
      @endforeach
      <tr align="center">
          <td colspan="10">{{ $collection->links("pagination::bootstrap-4") }}</td>
      </tr>
      </tbody>
    </table> 
</div>
@endsection
