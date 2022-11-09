@extends('users.show')
 
@section('body')

    @include('accounts.shared.search', [
        'route' => route('users.show', ['user' => $entity->getId()]), 
        'exclude'  => ['areas'],
    ])

    @include ('accounts.shared.table', [
        'collection' => $collection, 
        'pagination' => true, 
        'exclude'    => ['areas']
    ])


@endsection

