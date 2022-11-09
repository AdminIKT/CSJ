@extends('users.show')
@section('body')
    @include('orders.shared.search', [
        'route'    => route('users.orders.index', ['user' => $entity->getId()]), 
        'areas'    => Arr::pluck($entity->getAreas(), 'name', 'id'),
        'accounts' => Arr::pluck($entity->getAccounts(), 'name', 'id'),
    ])
    @include ('orders.shared.table', [
        'collection' => $collection, 
        'pagination' => true, 
        'exclude' => ['entity']
    ])
@endsection
