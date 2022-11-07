<!-- FIXME in Shared view -->
@extends('sj_layout')
@section('title'){{ __('Accounts') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('accounts.create') }}" class="btn btn-sm btn-outline-secondary m-1 ms-0" title="{{__('New')}}">
        <span data-feather="plus"></span> {{__('New')}}
    </a>
@endsection
@section('content')
<form action="{{ route('accounts.index') }}" method="GET" class="row mb-3">
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('Name') }}</span>
          {{ Form::text('name', request()->input('name'), ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-area">{{ __('Area') }}</span>
          {{ Form::select('area', [null => __('selecciona')] + $areas, request()->input('area'), ['class'=>'form-select', 'aria-describedby' => 'addon-area']) }}
        </div>
    </div>
    <div class="col">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('Type') }}</span>
          {{ Form::select('type', [
              null => __('selecciona'),
              \App\Entities\Account::TYPE_EQUIPAMIENTO => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_EQUIPAMIENTO),
              \App\Entities\Account::TYPE_FUNGIBLE => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_FUNGIBLE),
              \App\Entities\Account::TYPE_LANBIDE => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_LANBIDE),
              \App\Entities\Account::TYPE_OTHER => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_OTHER),
          ], request()->input('type'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
        </div>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="basic-addon1">{{ __('Real credit') }}</span>
                  <div class="input-group-prepend">
                      {{ Form::select('creditOp', [
                        ">=" => ">=",
                        "="  => "==",
                        "<=" => "<=",
                      ], request()->input('creditOp'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
                  </div>
                  {{ Form::number('credit', request()->input('credit'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
                  <span class="input-group-text">€</span>
                </div>
            </div>
            <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="basic-addon1">{{ __('Compromised credit') }}</span>
                  <div class="input-group-prepend">
                      {{ Form::select('compromisedOp', [
                        ">=" => ">=",
                        "="  => "==",
                        "<=" => "<=",
                      ], request()->input('compromisedOp'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
                  </div>
                  {{ Form::number('compromised', request()->input('compromised'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
                  <span class="input-group-text">€</span>
                </div>
            </div>
            <div class="col">
                <div class="input-group input-group-sm mb-3">
                  <span class="input-group-text" id="basic-addon1">{{ __('Available credit') }}</span>
                  <div class="input-group-prepend">
                      {{ Form::select('availableOp', [
                        ">=" => ">=",
                        "="  => "==",
                        "<=" => "<=",
                      ], request()->input('availableOp'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
                  </div>
                  {{ Form::number('available', request()->input('available'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
                  <span class="input-group-text">€</span>
                  <button class="btn btn-primary" type="submit" id="button-addon2">
                    <span data-feather="search"></span>
                  </button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    <div class="table-responsive">
      <table class="table table-sm align-middle">
        <thead>
        <tr>
            <th scope="col">{{ __('acronimo') }}
                <a class="{{ request()->get('sortBy') == 'account.name' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.name', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'account.name' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.name', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th scope="col">{{ __('tipo') }}</th>
            <th scope="col">{{ __('Areas') }}</th>
            <th scope="col">{{ __('Real credit') }}
                <a class="{{ request()->get('sortBy') == 'account.credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.credit', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'account.credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.credit', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th scope="col">{{ __('Compromised credit') }}
                <a class="{{ request()->get('sortBy') == 'account.compromisedCredit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.compromisedCredit', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'account.compromisedCredit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.compromisedCredit', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th scope="col">{{ __('Available credit') }}
            </th>
            <th scope="col">{{ __('Created') }}
                <a class="{{ request()->get('sortBy') == 'account.created' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.created', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'account.created' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.created', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
            <th scope="col">{{ __('Actions') }}</th>
        </tr>
        </thead>
        <tbody> 
            @foreach ($accounts as $i => $entity)
            <tr>
                <td>
                    <a href="{{ route('accounts.show', ['account' => $entity->getId()]) }}" data-bs-toggle="tooltip">{{ $entity->getSerial() }}</a>
                    <span class="small text-muted">{{ $entity->getName() }}</span>
                </td>
                <td>{{ $entity->getTypeName() }}</td>
                <td>{{ implode(", ", $entity->getAreas()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
                <td>{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($entity->getCompromisedCredit(), 2, ",", ".") }}€</td>
                <td>{{ number_format($entity->getAvailableCredit(), 2, ",", ".") }}€</td>
                <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
                <td>
                {{ Form::open([
                    'route' => ['accounts.destroy', $entity->getId()], 
                    'method' => 'delete',
                ]) }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('accounts.show', ['account' => $entity->getId()]) }}" class="btn btn-outline-secondary">
                        <span data-feather="eye"></span>
                    </a>
                    <a href="{{ route('accounts.edit', ['account' => $entity->getId()]) }}" class="btn btn-outline-secondary">
                        <span data-feather="edit-2"></span>
                    </a>
                    {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit']) }}
                </div>
                {{ Form::close() }}
                </td>
            </tr>
            @endforeach
            </tbody> 
        </table> 
        <div class="col-12">
            {{ $accounts->links("pagination::bootstrap-4") }}
        </div>
    </div>
</div>
@endsection
