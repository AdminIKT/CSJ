@extends('new_layout')
@section('title'){{ __('New movement') }}@endsection

@section('content')

<ul class="nav nav-tabs justify-content-center mb-3">
  <li class="nav-item">
    <a class='nav-link {{request()->is("movements/create")?" active":"" }}' href="{{ route('movements.create') }}">{{ __('Cargo por factura') }}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="">{{ __('Cobro en caja') }}</a>
  </li>
</ul>

    {{ Form::open([
        'route' => ['movements.store'], 
        'method' => 'POST', 
        'class' => 'row',
        'novalidate' => true,
        ]) 
    }}

    <div class="col-md-6 mb-3">
        {{ Form::label('credit', 'Credit', ['class' => 'form-label']) }}
        <div class="input-group input-group-sm">
            {{ Form::number('credit', null, ['step' => '0.01', 'min' => 0, 'class' => 'form-control' . ($errors->has('credit') ? ' is-invalid':'') ]) }}
            <span class="input-group-text">€</span>
            @if ($errors->has('credit'))
               <div class="invalid-feedback">{!! $errors->first('credit') !!}</div>
            @endif
        </div>

        {{ Form::label('invoice', 'Invoice nº', ['class' => 'form-label']) }}
        {{ Form::text('invoice', null, ['class' => 'form-control form-control-sm' . ($errors->has('invoice') ? ' is-invalid':'')]) }}
        @if ($errors->has('invoice'))
           <div class="invalid-feedback">{!! $errors->first('invoice') !!}</div>
        @endif
    </div>

    <div class="col-md-6 mb-3">
        {{ Form::label('detail', 'Description', ['class' => 'form-label']) }}
        {{ Form::textarea('detail', null, ['class' => 'form-control form-control-sm' . ($errors->has('detail') ? ' is-invalid':''), 'rows' => 4]) }}
        @if ($errors->has('detail'))
           <div class="invalid-feedback">{!! $errors->first('detail') !!}</div>
        @endif
    </div>

    <div class="col-md-12">
        <a href="{{ url()->previous() }}" class="btn btn-sm">Cancel</a>
        {{ Form::submit('Save', ['class' => 'btn btn-sm btn-primary float-end']) }}
    </div>

    {{ Form::close() }}

@endsection
