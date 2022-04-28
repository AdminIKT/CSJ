@extends('accounts.show')
 
@section('body')
    @include('movements.shared.search', [
        'route' => route('accounts.movements.index', ['account' => $entity->getId()]),
        'exclude' => []
    ])
    @include ('movements.shared.table', [
        'collection' => $collection, 
        'pagination' => true, 
        'exclude' => []
    ])
@endsection
