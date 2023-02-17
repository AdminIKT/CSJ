@extends('sj_layout')
@section('title'){{ __('Areas') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('areas.create') }}" class="btn btn-sm btn-outline-secondary m-1 ms-0">
        <span data-feather="plus"></span> {{__('New')}}
    </a>
@endsection
@section('content')

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    <div class="table-responsive">
        <table class="table table-sm align-middle">
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
                <td><a href="{{ route('areas.show', ['area' => $entity->getId()]) }}">{{ $entity->getAcronym() }}</a></td>
                <td>{{ $entity->getName() }}</td>
                <td title='{{ implode(", ", $entity->getAccounts()->map(function ($e) { return "{$e->getName()} ({$e->getType()})"; })->toArray())}}'>
                    {{ Str::limit(implode(", ", $entity->getAccounts()->map(function ($e) { return "{$e->getSerial()}"; })->toArray()), 75, '...') }}
                </td>
                <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
                <td>
                {{ Form::open([
                    'route' => ['areas.destroy', $entity->getId()], 
                    'method' => 'delete',
                ]) }}
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('areas.show', ['area' => $entity->getId()]) }}" class="btn btn-light">
                            <span class="bx bxs-show"></span>
                        </a>
                        <a href="{{ route('areas.edit', ['area' => $entity->getId()]) }}" class="btn btn-light">
                            <span class="bx bxs-pencil"></span>
                        </a>
                        {{ Form::button('<i class="bx bxs-trash-alt"></i>', [
                            'class'   => 'btn btn-light', 
                            'type'    => 'submit',
                            'onclick' => "return confirm('".__('delete.confirm')."')",
                        ]) }}
                    </div>
                {{ Form::close() }}
                </td>
            </tr>
            @endforeach
            </tbody> 
        </table>
    </div>
</div>
@endsection
