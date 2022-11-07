@extends('accounts.show')
 
@section('body')

    @include('orders.shared.search', [
        'route' => route('accounts.show', ['account' => $entity->getId()]), 
        'areas'    => Arr::pluck($entity->getAreas(), 'name', 'id'),
        'exclude'  => ['accounts', 'types', $entity->getAreas()->count() === 1 ? 'areas' : null],
    ])

    @include ('orders.shared.table', [
        'collection' => $collection, 
        'pagination' => true, 
        'exclude'    => ['accounts', 'types', $entity->getAreas()->count() === 1 ? 'areas' : null]
    ])


@endsection

