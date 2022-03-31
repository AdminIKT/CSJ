@extends('new_layout')
@section('title'){{ __('Areas') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('areas.create') }}" class="btn btn-sm btn-outline-secondary" title="{{__('New')}}">
        <span data-feather="plus"></span> {{__('New')}}
    </a>
@endsection
@section('content')

<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
    <thead>
    <tr>
        <th scope="col">{{ __('nombre') }}</th>
        <th scope="col">{{ __('acronimo') }}</th>
        <th scope="col">{{ __('tipo') }}</th>
        <th scope="col">{{ __('departamentos') }}</th>
        <th scope="col">{{ __('Real credit') }}</th>
        <th scope="col">{{ __('Compromised credit') }}</th>
        <th scope="col">{{ __('Available credit') }}</th>
        <th scope="col">{{ __('Created') }}</th>
        <th scope="col">{{ __('Actions') }}</th>
    </tr>
    </thead>
    <tbody> 
        @foreach ($areas as $i => $entity)
        <tr>
            <td>{{ $entity->getName() }}</td>
            <td>{{ $entity->getSerial() }}</td>
            <td>{{ $entity->getTypeName() }}</td>
            <td>{{ implode(", ", $entity->getDepartments()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
            <td>{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getCompromisedCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getAvailableCredit(), 2, ",", ".") }}€</td>
            <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
            <td>
            {{ Form::open([
                'route' => ['areas.destroy', $entity->getId()], 
                'method' => 'delete',
            ]) }}
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('areas.show', ['area' => $entity->getId()]) }}" class="btn btn-outline-secondary">
                    <span data-feather="eye"></span>
                </a>
                <a href="{{ route('areas.edit', ['area' => $entity->getId()]) }}" class="btn btn-outline-secondary">
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
</div>

@endsection
