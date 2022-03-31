@extends('suppliers.show')
 
@section('body')
@include('movements.shared.search', ['route' => route('suppliers.movements.index', ['supplier' => $entity->getId()])])
@include('movements.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['suppliers']])
@endsection
