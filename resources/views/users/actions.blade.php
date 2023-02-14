@extends('users.show')
@section('body')
    @include ('actions.shared.table', [
        'collection' => $collection, 
        'pagination' => true
    ])
@endsection
