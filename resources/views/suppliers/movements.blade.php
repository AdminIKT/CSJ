@extends('suppliers.show')
 
@section('body')
@include('invoiceCharges.shared.search', ['route' => route('suppliers.invoiceCharges.index', ['supplier' => $entity->getId()])])
@include('invoiceCharges.shared.table', ['collection' => $collection, 'pagination' => true, 'exclude' => ['suppliers']])
@endsection
