@extends('new_layout')
@section('title')
@if ($entity->getId()) 
    {{ __('Edit area :name', ['name' => $entity->getName()]) }} 
@else 
    {{ __('New area') }} 
@endif
@endsection

@section('content')
   
<form action="{{ $route }}" method="POST" class="row" novalidate>
    @csrf
    {{ method_field($method) }}

    <div class="col-md-4 mb-3">
        {{ Form::label('acronym', __('acronimo'), ['class' => 'form-label']) }}
        {{ Form::text('acronym', old('acronym', $entity->getAcronym()), ['class' => 'form-control form-control-sm' . ($errors->has('acronym') ? ' is-invalid':'')]) }}
        @if ($errors->has('acronym'))
           <div class="invalid-feedback">{!! $errors->first('acronym') !!}</div>
        @endif
    </div>

    <div class="col-md-8 mb-3 has-validations">
        {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
        {{ Form::text('name', old('name', $entity->getName()), ['class' => 'form-control form-control-sm' . ($errors->has('name') ? ' is-invalid' :'')]) }}
        @if ($errors->has('name'))
           <div class="invalid-feedback">{!! $errors->first('name') !!}</div>
        @endif
    </div>

    <fieldset class="mb-3">
        <legend>{{ __('Children')}}</legend>
        @php $cols = 10; $i=0; @endphp
        <table class="table">
        @for ($row=0; $row < count($departments)/$cols; $row++)
            <tr>
            @for ($col=0; $col < $cols; $col++)
                <td class="">
                @if (isset($departments[$i]))
                    @php $e = $departments[$i]; $i++; @endphp
                    {{ Form::checkbox("children[]", $e->getId(), $entity->getChildren()->contains($e), ['class' => 'form-check-input']) }}
                    {{ Form::label("children[]", $e->getName(), ['class' => 'form-check-label']) }}
                @endif
                </td>
            @endfor
            </tr>
        @endfor
        </table>
    </fieldset>

    <div>
        {{ Form::submit(__('guardar'), ['class' => 'btn btn-primary btn-sm float-end']) }}
        <a href="{{ url()->previous() }}" class="btn btn-sm">{{__('cancelar')}}</a>
    </div>

</form> 
@endsection
