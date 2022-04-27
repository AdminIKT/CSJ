@extends('suppliers.show')
 
@section('body')
    @include('orders.shared.search', ['route' => route('accounts.orders.index', ['account' => $entity->getId()])])
    @include ('orders.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['suppliers']])
@endsection
