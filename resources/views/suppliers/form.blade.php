@extends('sj_layout')
@section('title')
@if ($entity->getId()) 
    {{ __('Edit supplier :name', ['name' => $entity->getName()]) }} 
@else 
    {{ __('New supplier') }} 
@endif
@endsection
 
@section('content')
<form action="{{ $route }}" method="POST" class="" novalidate>
    @csrf
    {{ method_field($method) }}

    <div class="row">
        @can('viewAny', App\Entities\Supplier::class)
        <div class="col mb-3">
            {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
            <div class="input-group input-group-sm">
            <span class="input-group-text">
                <i class="cbg {!! $entity->getStatusColor() !!}"></i>
            </span>
            {{ Form::select('status', [
                null => __('selecciona'),
                \App\Entities\Supplier::STATUS_PENDING       => \App\Entities\Supplier::statusName(\App\Entities\Supplier::STATUS_PENDING),
                \App\Entities\Supplier::STATUS_VALIDATED     => \App\Entities\Supplier::statusName(\App\Entities\Supplier::STATUS_VALIDATED),
                \App\Entities\Supplier::STATUS_RECOMMENDABLE => \App\Entities\Supplier::statusName(\App\Entities\Supplier::STATUS_RECOMMENDABLE),
                \App\Entities\Supplier::STATUS_NO_ACCEPTABLE => \App\Entities\Supplier::statusName(\App\Entities\Supplier::STATUS_NO_ACCEPTABLE),
            ], old('status', $entity->getStatus()), ['class'=>'form-select form-select-sm' . ($errors->has('status') ? ' is-invalid':'')], [null => ['disabled' => true]]) }}
            @if ($errors->has('status'))
               <div class="invalid-feedback">{!! $errors->first('status') !!}</div>
            @endif
            </div>
        </div>
        @endcan

        <div class="col mb-3">
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', old('name', $entity->getName()), ['class' => 'form-control form-control-sm' . ($errors->has('name') ? ' is-invalid' :'')]) }}
            @if ($errors->has('name'))
               <div class="invalid-feedback">{!! $errors->first('name') !!}</div>
            @endif
        </div>

        <div class="col mb-3">
            {{ Form::label('nif', __('NIF'), ['class' => 'form-label']) }}
            {{ Form::text('nif', old('nif', $entity->getNif()), ['class' => 'form-control form-control-sm' . ($errors->has('nif') ? ' is-invalid' :'')]) }}
            @if ($errors->has('nif'))
               <div class="invalid-feedback">{!! $errors->first('nif') !!}</div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-2 mb-2">
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

        <div class="col-md-2 mb-3">
            {{ Form::label('region', __('Region'), ['class' => 'form-label']) }}
            {{ Form::text('region', old('region', $entity->getRegion()), ['class' => 'form-control form-control-sm' . ($errors->has('region') ? ' is-invalid' :'')]) }}
            @if ($errors->has('region'))
               <div class="invalid-feedback">{!! $errors->first('region') !!}</div>
            @endif
        </div>

        <div class="col-md-5 mb-3">
            {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
            {{ Form::text('address', old('address', $entity->getAddress()), ['class' => 'form-control form-control-sm' . ($errors->has('address') ? ' is-invalid' :'')]) }}
            @if ($errors->has('address'))
               <div class="invalid-feedback">{!! $errors->first('address') !!}</div>
            @endif
        </div>
        <div class="col-md-12 mb-3">
            {{ Form::label('detail', __('Detail'), ['class' => 'form-label mt-3']) }}
            {{ Form::textarea('detail', old('detail', null), ['class' => 'form-control form-control-sm', 'rows' => 2]) }}
            @if ($errors->has('detail'))
               <div class="invalid-feedback">{!! $errors->first('detail') !!}</div>
            @endif
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
        <!--{{ Form::submit(__('guardar'), ['class' => 'btn btn-sm btn-primary float-end']) }}-->
        <button type="submit" class="btn btn-sm btn-primary float-end">
            <i class='bx bxs-save'></i> {{ __('guardar') }}
        </button>
        @if (!$entity->getId())
            <button type="button" class="add-to-collection btn btn-sm btn-outline-primary mx-2 float-end">
                <i class='bx bx-plus'></i><i class='bx bx-user'></i> {{__('New contact')}}
            </button>
        @endif
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">
            <i class='bx bx-x'></i> {{__('cancelar')}}
        </a>
    </div>
    @if (isset($dst))
        {{ Form::hidden('destination', $dst ?? '') }}
    @endif

    {{ Form::close() }}

@endsection

@section('scripts')
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
