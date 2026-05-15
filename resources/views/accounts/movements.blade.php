@extends('accounts.show')
 
@section('body')
    <div class="float-end">
        <a class="btn btn-light" 
            title="{{ __('Pdf') }}"
            href="{{ route('accounts.movements.pdf', ['account' => $entity->getId()]) }}" 
            target="_blank">
            <span class="bx bx-xs bxs-file-pdf bx-tada-hover"></span>
        </a>
    </div>
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
