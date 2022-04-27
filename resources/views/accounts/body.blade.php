@extends('accounts.show')
 
@section('body')
    @include('orders.shared.search', ['route' => route('accounts.show', ['account' => $entity->getId()]), 'exclude' => ['accounts', 'types']])
    @include ('orders.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['accounts', 'types']])
@endsection

