@extends('accounts.show')
 
@section('body')
    @include('movements.shared.search', [
        'route' => route('accounts.movements.index', ['account' => $entity->getId()]),
        'areas'   => Arr::pluck($entity->getAreas(), 'name', 'id'),
        'exclude' => ['accounts', $entity->getAreas()->count() === 1 ? 'areas' : null],
    ])
    @include ('movements.shared.table', [
        'collection' => $collection, 
        'pagination' => true, 
        'exclude'    => ['accounts', $entity->getAreas()->count() === 1 ? 'areas' : null],
    ])
@endsection
