@extends('orders.show')
@section('body')
    @include ('suppliers.shared.incidences', ['entity' => $entity, 'pagination' => true, 'exclude' => ['orders']])
@endsection
