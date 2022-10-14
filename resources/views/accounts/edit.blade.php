@extends('new_layout')
@section('title')
@if ($entity->getId()) 
    {{ __('Edit account :name', ['name' => $entity->getSerial()]) }} 
@else 
    {{ __('New account') }} 
@endif
@endsection

@section('content')
<form action="{{ $route }}" method="POST" class="row" novalidate>
    @csrf
    {{ method_field($method) }}

    <div class="col-3 mb-3">
        {{ Form::label('acronym', __('acronimo'), ['class' => 'form-label']) }}
        {{ Form::text('acronym', old('acronym', $entity->getAcronym()), ['class' => 'form-control form-control-sm' . ($errors->has('acronym') ? ' is-invalid':'')]) }}
        @if ($errors->has('acronym'))
           <div class="invalid-feedback">{!! $errors->first('acronym') !!}</div>
        @endif
    </div>
    
    <div class="col-9 mb-3">
        {{ Form::label('name', __('nombre'), ['class' => 'form-label']) }}
        {{ Form::text('name', old('name', $entity->getName()), ['class' => 'form-control form-control-sm' . ($errors->has('name') ? ' is-invalid':'')]) }}
        @if ($errors->has('name'))
           <div class="invalid-feedback">{!! $errors->first('name') !!}</div>
        @endif
    </div>
    <div class="col-12 mb-3">
        {{ Form::label('detail', __('detalle'), ['class' => 'form-label mt-3']) }}
        {{ Form::textarea('detail', old('detail', $entity->getDetail()), ['class' => 'form-control form-control-sm', 'rows' => 2]) }}
        @if ($errors->has('detail'))
           <div class="invalid-feedback">{!! $errors->first('detail') !!}</div>
        @endif
    </div>

    <fieldset class="mb-3">
        <legend> {{ __('usuarios') }}</legend>
        @php $cols = 10; $i=0; @endphp
        <table class="table">
        @for ($row=0; $row < count($users)/$cols; $row++)
            <tr>
            @for ($col=0; $col < $cols; $col++)
                <td class="">
                @if (isset($users[$i]))
                    @php $e = $users[$i]; $i++; @endphp
                    {{ Form::checkbox("users[]", $e->getId(), $entity->getusers()->contains($e), ['class' => 'form-check-input']) }}
                    {{ Form::label("users[]", $e->getEmail(), ['class' => 'form-check-label']) }}
                @endif
                </td>
            @endfor
            </tr>
        @endfor
        </table>
    </fieldset>

    <div>
        {{ Form::submit(__('guardar'), ['class' => 'btn btn-primary btn-sm float-end']) }}
        <a href="{{ url()->previous() }}" class="btn btn-sm">
            {{ __('cancelar')}}
        </a>
    </div>

</form>
@endsection
