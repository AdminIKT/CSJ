@extends('areas.show')
 
@section('body')
    @include('assignments.shared.search', ['route' => route('areas.assignments.index', ['area' => $entity->getId()]), 'exclude' => ['areas']])
    @include ('assignments.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['areas']])
@endsection
