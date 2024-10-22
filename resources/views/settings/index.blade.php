@extends('sj_layout')
@section('title'){{ __('Settings') }}@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-auto">

    @foreach ($collection as $entity)
        @if($entity->getId() != 1)
            <h6>{{ $entity->getTypeName() }}</h6>
            <p>{{ $entity->getTypeDescription() }}<!--: <strong>{{ $entity->getValue() }}</strong> <a href="{{ route('settings.edit', ['setting' => $entity->getId()]) }}" class="btn btn-sm btn-outline-secondary mx-2"><span data-feather="edit-2"></span></a>--></p>
            <div class="d-flex justify-content-end border-bottom mb-3">
                <form action="{{ route('settings.update', ['setting' => $entity->getId()]) }}" method="POST">

                    @csrf
                    @method('PUT')

                        <div class="input-group input-group-sm mb-3">
                        {{ Form::number('value', $entity->getValue(), ['class' => 'form-control']) }}
                        <button class="btn btn-outline-primary" type="submit" id="button-addon2">{{ __('Save') }}</button>
                        </div>
                    </p>
                </form>
            </div>
        @endif
    @endforeach
  
    </div>
</div>
@endsection

