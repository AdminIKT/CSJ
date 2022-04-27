@extends('new_layout')
@section('title'){{ __('Areas') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('areas.create') }}" class="btn btn-sm btn-outline-secondary">
    <span data-feather="plus"></span> {{__('New')}}
    </a>
@endsection
@section('content')

<div class="table-responsive">
<table class="table table-hover table-sm align-middle">
    <thead>
    <tr>
        <th scope="col">{{ __('Acronym') }}</th>
        <th scope="col">{{ __('Name') }}</th>
        <th scope="col">{{ __('Accounts') }}</th>
        <th scope="col">{{ __('Created') }}</th>
        <th scope="col">{{ __('Actions') }}</th>
    </tr>
    </thead>
    <tbody> 
    @foreach ($collection as $entity)
    <tr>
        <td>{{ $entity->getAcronym() }}</td>
        <td>{{ $entity->getName() }}</td>
        <td>{{ implode(", ", $entity->getAccounts()->map(function ($e) { return "{$e->getName()} ({$e->getType()})"; })->toArray()) }}</td>
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
@endsection
