@extends('new_layout')
@section('title'){{ __('Settings') }}@endsection

@section('content')

    <form action="{{ route('settings.update', ['setting' => $entity->getId()]) }}" 
        method="POST" 
        class="row" 
        novalidate>
    @csrf
    {{ method_field('PUT') }}
        <div class="col-12">
            {{ Form::label('value', $entity->getTypeName(), ['class' => 'form-label']) }}
            <div class="input-group input-group-sm mb-3">
                {{ Form::number('value', old('value', $entity->getValue()) , 
                    ['step' => '0.01', 'min' => 0, 'class' => 'form-control' . ($errors->has('value') ? ' is-invalid':'') ]) }}
                <span class="input-group-append">
                    {{ Form::submit(__('guardar'), ['name' => "form-{$entity->getType()}", 'class' => 'btn btn-sm btn-primary']) }}
                </span>
                @if (old('type') == $entity->getType() && $errors->has('value'))
                   <div class="invalid-feedback">{!! $errors->first('value') !!}</div>
                @endif
            </div>
            <input type="hidden" name="type"  value="{{ $entity->getType() }}" />
        </div> 
    </form> 
  
@endsection

