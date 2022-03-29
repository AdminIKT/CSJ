@extends('areas.show')
 
@section('body')
    @include('orders.shared.search', ['route' => route('areas.show', ['area' => $entity->getId()]), 'exclude' => ['areas']])
    @include ('orders.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['areas']])
@endsection

