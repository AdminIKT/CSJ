@extends('suppliers.show')

@section('body')
<h4 class="py-2">@if ($incidence->getId()) {{__('Edit incidence')}} @else {{__('New incidence')}} @endif</h4>
<form action="{{ $route }}" method="POST" class="row">
    @csrf
    {{ method_field($method ?? 'POST') }}
    <div class="col-md-12 mb-1 has-validations">
        {{ Form::label('detail', __('Detail'), ['class' => 'form-label']) }}
        {{ Form::textarea("detail", $incidence->getDetail(), ["class" => "form-control form-control-sm" . ($errors->has("detail") ? " is-invalid":""), 'rows' => 2]) }}
        @if ($errors->has("detail"))
           <div class="invalid-feedback">{!! $errors->first("detail") !!}</div>
        @endif
    </div>
    @if ($order)
        <input type="hidden" name="order" value="{{ $order ?? $order->getId() }}" />
    @endif
    <div class="col-md-12">
        <a href="{{ url()->previous() }}" class="btn btn-sm">{{__('cancelar')}}</a>
        {{ Form::submit(__('guardar'), ['class' => 'btn btn-sm btn-primary float-end']) }}
    </div>
</form>
@endsection
 
