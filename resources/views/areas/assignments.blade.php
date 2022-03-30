@extends('areas.show')
 
@section('body')
    @include ('assignments.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['areas']])
@endsection
