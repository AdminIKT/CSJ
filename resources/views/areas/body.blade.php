@extends('areas.show')
 
@section('body')

    @include('accounts.shared.search', [
        'route' => route('areas.show', ['area' => $entity->getId()]), 
        'exclude'  => ['areas'],
    ])

    @include ('accounts.shared.table', [
        'collection' => $collection, 
        'pagination' => true, 
        'exclude'    => ['areas']
    ])


@endsection

