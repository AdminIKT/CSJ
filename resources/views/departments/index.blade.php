@extends('new_layout')
@section('title'){{ __('Areas') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('departments.create') }}" class="btn btn-sm btn-outline-secondary">
    <span data-feather="plus"></span> {{__('New')}}
    </a>
@endsection
@section('content')

<div class="table-responsive">
<table class="table table-hover table-sm align-middle">
    <thead>
    <tr>
        <th scope="col">{{ __('Name') }}</th>
        <th scope="col">{{ __('Cuentas') }}</th>
        <th scope="col">{{ __('Created') }}</th>
        <th scope="col">{{ __('Actions') }}</th>
    </tr>
    </thead>
    <tbody> 
    @foreach ($collection as $entity)
    <tr>
        <td>{{ $entity->getName() }}</td>
        <td>{{ implode(", ", $entity->getAreas()->map(function ($e) { return "{$e->getName()} ({$e->getType()})"; })->toArray()) }}</td>
        <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        <td>
        {{ Form::open([
            'route' => ['departments.destroy', $entity->getId()], 
            'method' => 'delete',
        ]) }}
            <div class="btn-group btn-group-sm" role="group">
                <a href="{{ route('departments.show', ['department' => $entity->getId()]) }}" class="btn btn-outline-secondary">
                    <span data-feather="eye"></span>
                </a>
                <a href="{{ route('departments.edit', ['department' => $entity->getId()]) }}" class="btn btn-outline-secondary">
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
@endsection
