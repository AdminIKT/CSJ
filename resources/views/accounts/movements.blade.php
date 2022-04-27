@extends('accounts.show')
 
@section('body')
    @include('movements.shared.search', ['route' => route('accounts.movements.index', ['account' => $entity->getId()]), 'exclude' => ['accounts']])
    @include ('movements.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['accounts']])
@endsection
