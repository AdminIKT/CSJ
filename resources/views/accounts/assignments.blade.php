@extends('accounts.show')
 
@section('body')
    @include('assignments.shared.search', ['route' => route('accounts.assignments.index', ['account' => $entity->getId()]), 'exclude' => ['accounts']])
    @include ('assignments.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['accounts']])
@endsection
