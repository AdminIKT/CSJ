<!-- FIXME in Shared view -->
@extends('new_layout')
@section('title'){{ __('Accounts') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('accounts.create') }}" class="btn btn-sm btn-outline-secondary" title="{{__('New')}}">
        <span data-feather="plus"></span> {{__('New')}}
    </a>
@endsection
@section('content')
<form action="{{ route('accounts.index') }}" method="GET" class="row mb-3">
    <div class="col-2">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('Name') }}</span>
          {{ Form::text('name', request()->input('name'), ['class' => 'form-control']) }}
        </div>
    </div>
    <div class="col-2">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="addon-type">{{ __('Type') }}</span>
          {{ Form::select('type', [
              null => __('selecciona'),
              \App\Entities\Account::TYPE_EQUIPAMIENTO => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_EQUIPAMIENTO),
              \App\Entities\Account::TYPE_FUNGIBLE => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_FUNGIBLE),
              \App\Entities\Account::TYPE_LANBIDE => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_LANBIDE),
          ], request()->input('type'), ['class'=>'form-select', 'aria-describedby' => 'addon-type']) }}
        </div>
    </div>
    <div class="col-4">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('Real credit') }}</span>
          <div class="input-group-prepend">
              {{ Form::select('creditOp', [
                ">=" => ">=",
                "==" => "==",
                "<=" => "<=",
              ], request()->input('creditOp'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
          </div>
          {{ Form::number('credit', request()->input('credit'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
          <span class="input-group-text">€</span>
        </div>
    </div>
    <div class="col-4">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="basic-addon1">{{ __('Compromised credit') }}</span>
          <div class="input-group-prepend">
              {{ Form::select('compromisedOp', [
                ">=" => ">=",
                "==" => "==",
                "<=" => "<=",
              ], request()->input('compromisedOp'), ['class'=>'form-select form-select-sm', 'aria-describedby' => 'addon-type']) }}
          </div>
          {{ Form::number('compromised', request()->input('compromised'), ['class' => 'form-control', 'step' => '0.01', 'min' => 0]) }}
          <span class="input-group-text">€</span>
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
        <th scope="col">{{ __('nombre') }}
            <a class="{{ request()->get('sortBy') == 'name' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'name', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'name' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'name', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('tipo') }}</th>
        <th scope="col">{{ __('Areas') }}</th>
        <th scope="col">{{ __('Real credit') }}
            <a class="{{ request()->get('sortBy') == 'credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'credit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'credit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('Compromised credit') }}
            <a class="{{ request()->get('sortBy') == 'compromisedCredit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'compromisedCredit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'compromisedCredit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'compromisedCredit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('Available credit') }}</th>
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
        @foreach ($accounts as $i => $entity)
        <tr>
            <td title="{{ $entity->getName() }}">{{ $entity->getAcronym() }}-{{ $entity->getType() }}</td>
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
    <div class="col-12">{{ $accounts->links("pagination::bootstrap-4") }}</div>
</div>

@endsection
