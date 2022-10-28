@extends('orders.show')
@section('body')
    @include ('actions.shared.table', ['collection' => $entity->getActions(), 'pagination' => true, 'exclude' => ['orders']])
@endsection
