@extends('users.show')
@section('body')
    @include('orders.shared.search', [
        'route' => route('users.orders.index', ['user' => $entity->getId()]), 
        'accounts' => Arr::pluck($entity->getAccounts(), 'name', 'id'),
        'exclude'  => ['areas'],
    ])
    @include ('orders.shared.table', [
        'collection' => $collection, 
        'pagination' => true, 
        'exclude' => ['entity']
    ])
@endsection
