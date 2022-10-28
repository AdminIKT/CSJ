@extends('orders.show')
@section('body')
    @include ('actions.shared.table', [
        'collection' => $collection, 
        'pagination' => true, 
        'exclude' => ['entity']
    ])
@endsection
