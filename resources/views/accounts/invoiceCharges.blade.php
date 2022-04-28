@extends('accounts.show')
 
@section('body')
    @include('invoiceCharges.shared.search', ['route' => route('accounts.invoiceCharges.index', ['account' => $entity->getId()]), 'exclude' => ['accounts']])
    @include ('invoiceCharges.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['accounts']])
@endsection
