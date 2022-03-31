@extends('suppliers.show')
 
@section('body')
    @include('orders.shared.search', ['route' => route('areas.orders.index', ['area' => $entity->getId()])])
    @include ('orders.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['suppliers']])
@endsection
