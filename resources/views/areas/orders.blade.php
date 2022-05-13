@extends('areas.show')
 
@section('body')
    @include('orders.shared.search', [
        'route'   => route('areas.orders.index', ['area' => $entity->getId()]),
        'exclude' => ['areas', count($accounts) == 1 ? 'accounts' : null],
    ])
    @include ('orders.shared.table', [
        'collection' => $collection, 
        'pagination' => true, 
        'exclude'    => ['areas'],
    ])
@endsection
