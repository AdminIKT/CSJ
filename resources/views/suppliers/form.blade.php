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
        {{ Form::label('name', 'Name', ['class' => 'form-label']) }}
        {{ Form::text('name', old('name', $entity->getName()), ['class' => 'form-control form-control-sm' . ($errors->has('name') ? ' is-invalid' :'')]) }}
        @if ($errors->has('name'))
           <div class="invalid-feedback">{!! $errors->first('name') !!}</div>
        @endif
    </div>

    <div class="col-md-6 mb-3">
        {{ Form::label('nif', 'NIF', ['class' => 'form-label']) }}
        {{ Form::number('nif', old('nif', $entity->getNif()), ['class' => 'form-control form-control-sm' . ($errors->has('nif') ? ' is-invalid' :'')]) }}
        @if ($errors->has('nif'))
           <div class="invalid-feedback">{!! $errors->first('nif') !!}</div>
        @endif
    </div>

    <div class="col-md-3 mb-3">
        {{ Form::label('zip', 'ZIP code', ['class' => 'form-label']) }}
        {{ Form::number('zip', old('zip', $entity->getZip()), ['class' => 'form-control form-control-sm' . ($errors->has('zip') ? ' is-invalid' :'')]) }}
        @if ($errors->has('zip'))
           <div class="invalid-feedback">{!! $errors->first('zip') !!}</div>
        @endif
    </div>

    <div class="col-md-3 mb-3">
        {{ Form::label('city', 'City', ['class' => 'form-label']) }}
        {{ Form::text('city', old('city', $entity->getCity()), ['class' => 'form-control form-control-sm' . ($errors->has('city') ? ' is-invalid' :'')]) }}
        @if ($errors->has('city'))
           <div class="invalid-feedback">{!! $errors->first('city') !!}</div>
        @endif
    </div>

    <div class="col-md-6 mb-3">
        {{ Form::label('address', 'Address', ['class' => 'form-label']) }}
        {{ Form::text('address', old('address', $entity->getAddress()), ['class' => 'form-control form-control-sm' . ($errors->has('address') ? ' is-invalid' :'')]) }}
        @if ($errors->has('address'))
           <div class="invalid-feedback">{!! $errors->first('address') !!}</div>
        @endif
    </div>
   

    <fieldset class="col-md-12 mb-3 collection-container" 
             data-prototype='@include("suppliers.shared.form_contact", ["index" => "__NAME__"])'>
        <legend>Contacts</legend>
        @foreach ($entity->getContacts() as $i => $contact)
            @include('suppliers.shared.form_contact', ['index' => $i])
        @endforeach
    </fieldset>


    <div class="col-md-12 mb-3">
        {{ Form::submit('Save', ['class' => 'btn btn-sm btn-primary float-end']) }}
        <button type="button" class="add-to-collection btn btn-sm btn-outline-primary float-end">New contact</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-default">Cancel</a>
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
                var count = container.children().length;
                var proto = container.data('prototype').replace(/__NAME__/g, count);
                container.append(proto);
            });
        });

        function rmCollection(btn) {
            btn.closest('div').remove();
        }
    </script>
@endsection
