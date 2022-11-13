@extends('users.show')
 
@section('body')

    @include('accounts.shared.search', [
        'route' => route('users.show', ['user' => $entity->getId()]), 
        'areas' => Arr::pluck($entity->getAreas(), 'name', 'id'),
        'exclude'  => ['status'],
    ])

    @include ('accounts.shared.table', [
        'collection' => $collection, 
        'pagination' => true, 
        'exclude'    => ['areas']
    ])


@endsection

