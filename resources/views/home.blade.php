@extends('new_layout')
@section('title'){{ __('Dashboard') }}@endsection
@section('btn-toolbar')
<!--
    <div class="btn-group me-2">
      <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
      <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
    </div>
    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
      <span data-feather="calendar"></span>
      This week
    </button>
-->
@endsection
@section('content')

<div class="row" data-masonry='{"percentPosition": true }'>
@foreach (Auth::user()->getAreas() as $area)
<div class="col-md-3 mb-3">
<div class="card text-center">
  <div class="card-header">{{ $area->getTypeName() }}</div>
  <div class="card-body">
    <h5 class="card-title">{{ $area->getName() }}</h5>
    <h6 class="card-subtitle mb-2 text-muted">{{ $area->getAcronym() }}</h6>
    <!--<p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>-->
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">
        <strong>{{ __('Areas') }}:</strong> {{ implode(", ", $area->getDepartments()->map(function ($e) { return $e->getName(); })->toArray()) }}
    </li>
    <li class="list-group-item">
        <strong>{{ __('Users') }}:</strong> {{ implode(", ", $area->getUsers()->map(function ($e) { return $e->getName(); })->toArray()) }}
    </li>
  </ul>
  <div class="card-body">
    <a href="{{ route('areas.show', ['area' => $area->getId()]) }}" class="btn btn-sm btn-outline-primary">
        <span data-feather="eye"></span> {{ __('View') }}</a>
    <a href="{{ route('areas.orders.create', ['area' => $area->getId()]) }}" class="btn btn-sm btn-outline-primary">
        <span data-feather="file"></span> {{ __('New order') }}</a>
  </div>
  <div class="card-footer text-muted">
    <strong>{{ __('saldo_disponible') }}:</strong> {{ number_format($area->getAvailableCredit(), 2, ",", ".") }}€
  </div>
</div>
</div>
@endforeach
</div>

@endsection
@section('styles')
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous" async></script>
@endsection
