@extends('sj_layout')
@section('title'){{ __('Settings') }}@endsection

@section('content')

    @foreach ($collection as $entity)
        @if($entity->getId() != 1)
            <h6>{{ $entity->getTypeName() }}</h6>
            <p>{{ $entity->getTypeDescription() }}: <strong>{{ $entity->getValue() }}</strong> <a href="{{ route('settings.edit', ['setting' => $entity->getId()]) }}" class="btn btn-sm btn-outline-secondary mx-2"><span data-feather="edit-2"></span></a></p>
            <hr>
        @endif
    @endforeach
  
@endsection

