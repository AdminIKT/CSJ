@extends('areas.show')
 
@section('body')
    @include('movements.shared.search', ['route' => route('areas.movements.index', ['area' => $entity->getId()]), 'exclude' => ['areas']])
    @include ('movements.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['areas']])
@endsection
