@extends('suppliers.show')
 
@section('body')
    @include('orders.shared.search', ['route' => route('suppliers.orders.index', ['supplier' => $entity->getId()])])
    @include ('orders.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['suppliers']])
@endsection
