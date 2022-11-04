@extends('sj_layout')
@section('title'){{ __('Users') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('users.create') }}" class="btn btn-sm btn-outline-secondary m-1 ms-0">
        <span data-feather="plus"></span> {{__('New')}}
    </a>
@endsection
@section('content')
<div class="bg-white border rounded rounded-5 px-2 mb-2">
    <div class="table-responsive">
        <table class="table table-sm align-middle">
            <thead>
            <tr>
                <th scope="col">{{ __('Avatar') }}</th>
                <th scope="col">{{ __('Email') }}</th>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Roles') }}</th>
                <th scope="col">{{ __('Accounts') }}</th>
                <th scope="col">{{ __('Created') }}</th>
                <th scope="col">{{ __('Last login') }}</th>
                <th scope="col">{{ __('Actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($collection as $entity)
            <tr>
                <td>@if ($entity->getAvatar()) <img src="{{ $entity->getAvatar() }}" height="25" width="25" class="rounded-circle"/> @endif</td>
                <td>{{ $entity->getEmail() }}</td>
                <td>{{ $entity->getName() }}</td>
                <td>{{ implode(", ", $entity->getRoles()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
                <td>{{ implode(", ", $entity->getAccounts()->map(function ($e) { return "{$e->getName()} ({$e->getType()})"; })->toArray()) }}</td>
                <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
                <td>@if ($entity->getLastLogin()) {{ $entity->getLastLogin()->format("d/m/Y H:i") }} @endif</td>
                <td class="m-0">
                    {{ Form::open([
                        'route' => ['users.destroy', $entity->getId()], 
                        'method' => 'delete',
                    ]) }}
        
                    <div class="btn-group btn-group-sm">
                        <a href="{{route('users.show', ['user' => $entity->getId()])}}" class="btn btn-outline-secondary">
                            <span data-feather="eye"></span>
                        </a>
                        <a href="{{route('users.edit', ['user' => $entity->getId()])}}" class="btn btn-outline-secondary">
                            <span data-feather="edit-2"></span>
                        </a>
                        {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit']) }}
                    </div>
                {{ Form::close() }}
                </td>
            </tr> 
            @endforeach
        </table>
    </div>
</div>
@endsection

