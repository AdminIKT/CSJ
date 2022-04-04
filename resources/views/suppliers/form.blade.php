@extends('new_layout')
@section('title')
@if ($entity->getId()) 
    {{ __('Edit supplier :name', ['name' => $entity->getName()]) }} 
@else 
    {{ __('New supplier') }} 
@endif
@endsection
 
@section('content')
<form action="{{ $route }}" method="POST" class="row" novalidate>
    @csrf
    {{ method_field($method) }}
   
    <div class="col-md-6 mb-3">
        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
        {{ Form::text('name', old('name', $entity->getName()), ['class' => 'form-control form-control-sm' . ($errors->has('name') ? ' is-invalid' :'')]) }}
        @if ($errors->has('name'))
           <div class="invalid-feedback">{!! $errors->first('name') !!}</div>
        @endif
    </div>

    <div class="col-md-6 mb-3">
        {{ Form::label('nif', __('NIF'), ['class' => 'form-label']) }}
        {{ Form::text('nif', old('nif', $entity->getNif()), ['class' => 'form-control form-control-sm' . ($errors->has('nif') ? ' is-invalid' :'')]) }}
        @if ($errors->has('nif'))
           <div class="invalid-feedback">{!! $errors->first('nif') !!}</div>
        @endif
    </div>

    <div class="col-md-3 mb-3">
        {{ Form::label('zip', __('Zip'), ['class' => 'form-label']) }}
        {{ Form::number('zip', old('zip', $entity->getZip()), ['class' => 'form-control form-control-sm' . ($errors->has('zip') ? ' is-invalid' :'')]) }}
        @if ($errors->has('zip'))
           <div class="invalid-feedback">{!! $errors->first('zip') !!}</div>
        @endif
    </div>

    <div class="col-md-3 mb-3">
        {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
        {{ Form::text('city', old('city', $entity->getCity()), ['class' => 'form-control form-control-sm' . ($errors->has('city') ? ' is-invalid' :'')]) }}
        @if ($errors->has('city'))
           <div class="invalid-feedback">{!! $errors->first('city') !!}</div>
        @endif
    </div>

    <div class="col-md-6 mb-3">
        {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
        {{ Form::text('address', old('address', $entity->getAddress()), ['class' => 'form-control form-control-sm' . ($errors->has('address') ? ' is-invalid' :'')]) }}
        @if ($errors->has('address'))
           <div class="invalid-feedback">{!! $errors->first('address') !!}</div>
        @endif
    </div>

    <div class="col-md-12 mb-3">
        <div class="form-check form-check-inline">
            {{ Form::checkbox('acceptable', true, old('acceptable', $entity->getAcceptable()), ['class' => 'form-check-input']) }}
            {{ Form::label('acceptable', __('Acceptable'), ['class' => 'form-check-label']) }}
        </div>
        <div class="form-check form-check-inline">
            {{ Form::checkbox('recommendable', true, old('recommendable', $entity->getRecommendable()), ['class' => 'form-check-input']) }}
            {{ Form::label('recommendable', __('Recommendable'), ['class' => 'form-check-label']) }}
        </div>
    </div>
   
    @if (!$entity->getId())
    <fieldset class="col-md-12 mb-3 collection-container" 
             data-prototype='@include("suppliers.shared.form_contact", ["index" => "__NAME__"])'>
        <legend>{{__('Contacts')}}</legend>
        @foreach (old('contacts', [[]]) as $i => $contact)
            @include('suppliers.shared.form_contact', ['index' => $i])
        @endforeach
    </fieldset>
    @endif

    <div class="col-md-12 mb-3">
        {{ Form::submit(__('guardar'), ['class' => 'btn btn-sm btn-primary float-end']) }}
        @if (!$entity->getId())
            <button type="button" class="add-to-collection btn btn-sm btn-outline-primary mx-2 float-end">{{__('New contact')}}</button>
        @endif
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-default">{{__('cancelar')}}</a>
    </div>

    {{ Form::close() }}

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.add-to-collection').on('click', function(e) {
                e.preventDefault();
                var container = $('.collection-container');
                var count = container.children('.contact').length;
                console.log(count);
                var proto = container.data('prototype').replace(/__NAME__/g, count);
                container.append(proto);
            });
        });

        function rmCollection(btn) {
            btn.closest('div').remove();
        }
    </script>
@endsection
