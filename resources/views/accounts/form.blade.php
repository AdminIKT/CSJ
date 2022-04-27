@extends('new_layout')
@section('title')
@if ($entity->getId()) 
    {{ __('Edit account :name', ['name' => $entity->getName()]) }} 
@else 
    {{ __('New account') }} 
@endif
@endsection

@section('content')
<form action="{{ $route }}" method="POST" class="row" novalidate>
    @csrf
    {{ method_field($method) }}

    <div class="col-3 mb-3">
        {{ Form::label('type', __('tipo'), ['class' => 'form-label']) }}
        {{ Form::select('type', [
            null => __('Selecciona'),
            \App\Entities\Account::TYPE_EQUIPAMIENTO => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_EQUIPAMIENTO),
            \App\Entities\Account::TYPE_FUNGIBLE => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_FUNGIBLE),
            \App\Entities\Account::TYPE_LANBIDE => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_LANBIDE),
        ], old('type', $entity->getType()), ['class'=>'form-select form-select-sm' . ($errors->has('type') ? ' is-invalid':'')], [null => ['disabled' => true]]) }}
        @if ($errors->has('type'))
           <div class="invalid-feedback">{!! $errors->first('type') !!}</div>
        @endif
    </div>

    <div class="col-9 mb-3">
        {{ Form::label('lcode', __('Code'), ['class' => 'form-label']) }}
        {{ Form::text('lcode', old('lcode', $entity->getLCode()), ['class' => 'form-control form-control-sm' . ($errors->has('lcode') ? ' is-invalid':''), 'disabled' => $entity->getType() !== \App\Entities\Account::TYPE_LANBIDE ]) }}
        @if ($errors->has('lcode'))
           <div class="invalid-feedback">{!! $errors->first('lcode') !!}</div>
        @endif
    </div>

    <div class="col-12 mb-3">
        {{ Form::label('detail', __('detalle'), ['class' => 'form-label mt-3']) }}
        {{ Form::textarea('detail', old('detail', $entity->getDetail()), ['class' => 'form-control form-control-sm', 'rows' => 2]) }}
        @if ($errors->has('detail'))
           <div class="invalid-feedback">{!! $errors->first('detail') !!}</div>
        @endif
    </div>

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

    <!--
    <div class="col-md-6 mb-3">
        {{ Form::label('credit', __('saldo'), ['class' => 'form-label']) }}
        <div class="input-group input-group-sm">
        {{ Form::number('credit', old('credit', $entity->getCredit()), ['class' => 'form-control' . ($errors->has('credit') ? ' is-invalid':''), 'step' => '0.01', 'min' => 0]) }}
        <span class="input-group-text">€</span>
        </div>
        @if ($errors->has('credit'))
           <div class="invalid-feedback">{!! $errors->first('credit') !!}</div>
        @endif
    </div>
    -->
    <fieldset class="mb-3">
        <!--<legend>{{ __('Subaccounts')}}</legend>-->
        @php $cols = 10; $i=0; @endphp
        <table class="table table-bordered">
        @for ($row=0; $row < count($areas)/$cols; $row++)
            <tr>
            @for ($col=0; $col < $cols; $col++)
                <td class="">
                @if (isset($areas[$i]))
                    @php $e = $areas[$i]; $i++; @endphp
                    {{ Form::checkbox("accounts[]", $e->getId(), $entity->getAreas()->contains($e), ['class' => 'form-check-input' . ($errors->has('accounts') ? ' is-invalid':''), 'onchange' => 'selectArea(this)']) }}
                    {{ Form::label("accounts[]", $e->getName(), ['class' => 'form-check-label']) }}
                @endif
                </td>
            @endfor
            </tr>
        @endfor
        </table>
    </fieldset>

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

@section('scripts')
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script>
    $(document).ready(function() {
        $('#lcode').attr('disabled', $('#type').val() != 'L');
        $('#type').change(function() {
            $('#lcode').val('').attr('disabled', $(this).val() != 'L');
        });
    });

    var areas = @php echo json_encode($areas); @endphp;
    function selectArea(input) {
        var count = $("input[name='accounts[]']:checkbox:checked").length;
        switch (count) {
            case 1:
                $("input[name='accounts[]']:checkbox:checked").each(function(i, checkbox) {
                    for (var j=0; j<areas.length; j++) {
                        if (areas[j].id == checkbox.value) {
                            $('#acronym:input').val(areas[j].acronym);
                            $('#name:input').val(areas[j].name);
                        }
                    }
                });
                break;
            default:
                $('#acronym:input').val(null);
                $('#name:input').val(null);
        }
    }
</script>
@endsection
