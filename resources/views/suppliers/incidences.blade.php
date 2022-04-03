@extends('suppliers.show')
@section('body')
    @include ('suppliers.shared.incidences', ['entity' => $entity, 'pagination' => true, 'exclude' => []])
@endsection
