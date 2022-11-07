@extends('sj_layout')
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
        {{ Form::label('type', __('tipo'), ['class' => 'form-label']) }}
        {{ Form::select('type', [
            null => __('Selecciona'),
            \App\Entities\Account::TYPE_EQUIPAMIENTO => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_EQUIPAMIENTO),
            \App\Entities\Account::TYPE_FUNGIBLE => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_FUNGIBLE),
            \App\Entities\Account::TYPE_LANBIDE => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_LANBIDE),
            \App\Entities\Account::TYPE_OTHER => \App\Entities\Account::typeName(\App\Entities\Account::TYPE_OTHER),
        ], old('type', $entity->getType()), ['class'=>'form-select form-select-sm' . ($errors->has('type') ? ' is-invalid':'')], [null => ['disabled' => true]]) }}
        @if ($errors->has('type'))
           <div class="invalid-feedback">{!! $errors->first('type') !!}</div>
        @endif
    </div>

    <div class="col-9 mb-3">
        {{ Form::label('lcode', __('Code'), [
            'class' => 'form-label',
            'disabled' => 'disabled',
        ]) }}
        {{ Form::text('lcode', old('lcode', $entity->getLCode()), ['class' => 'form-control form-control-sm' . ($errors->has('lcode') ? ' is-invalid':''), 'disabled' => $entity->getType() !== \App\Entities\Account::TYPE_LANBIDE ]) }}
        @if ($errors->has('lcode'))
           <div class="invalid-feedback">{!! $errors->first('lcode') !!}</div>
        @endif
    </div>

    {{ Form::label('accounts[]', __('Areas'), ['class' => 'form-label']) }}
    <fieldset class="mb-3 collection-container d-flex flex-wrap" 
             data-prototype='@include("accounts.shared.form_area", ["index" => "__NAME__"])'>
        @foreach (old('accounts', [[]]) as $i => $area)
            @include('accounts.shared.form_area', ['index' => $i])
        @endforeach
        <button type="button" class="add-to-collection btn btn-sm btn-outline-secondary">{{__('New area')}}</button>
    </fieldset>

    <div class="col-3 mb-3">
        {{ Form::label('acronym', __('acronimo'), ['class' => 'form-label']) }}
        <div class="input-group input-group-sm">
        {{ Form::text('acronym', old('acronym', $entity->getAcronym()), ['class' => 'form-control form-control-sm' . ($errors->has('acronym') ? ' is-invalid':'')]) }}
            <span class="input-group-text @if ($errors->has('acronym')) border-danger @endif" id="acr-addon" style="display:none"></span>
            <span class="input-group-text @if ($errors->has('acronym')) border-danger @endif" id="code-addon" style="display:none"></span>
            @if ($errors->has('acronym'))
           <div class="invalid-feedback">{!! $errors->first('acronym') !!}</div>
        @endif
        </div>
        
        @if ($errors->has('existe'))                    
           <div class="invalid-feedback"> {!! $errors->first('existe') !!}</div>
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
        <legend> {{ __('usuarios') }}</legend>
        @php $cols = 10; $i=0; @endphp
        <table class="table">
        @for ($row=0; $row < count($users)/$cols; $row++)
            <tr>
            @for ($col=0; $col < $cols; $col++)
                <td class="">
                @if (isset($users[$i]))
                    @php $e = $users[$i]; $i++; @endphp
                    {{ Form::checkbox("users[]", $e->getId(), $entity->getusers()->contains($e), ['class' => 'form-check-input' . ($errors->has('users') ? ' is-invalid':'')]) }}
                    {{ Form::label("users[]", $e->getEmail(), ['class' => 'form-check-label']) }}
                @endif
                </td>
            @endfor
            </tr>
        @endfor
        </table>
    </fieldset>

    <div>
        {{ Form::button(__('guardar'), ['type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-save float-end']) }}
        <a href="{{ url()->previous() }}" class="btn btn-sm">
            {{ __('cancelar')}}
        </a>
    </div>

</form>
@endsection

@section('scripts')
<script>

    $(document).ready(function() {
        changeTypeInput($('#type'));
        $('#type').change(function() {
            changeTypeInput($(this));
        });
        changeCodeInput($('#lcode'));
        $('#lcode').change(function() {
            changeCodeInput($(this));
        });
    });

    function changeTypeInput(input) {
        var value    = input.val();
        if (!value) return;
        var options  = ['L', 'O'];
        var disabled = jQuery.inArray(value, options) == -1;
        if (disabled) {
            $('#lcode').val('');
            changeCodeInput($('#lcode'));
        }
        $('#lcode').attr('disabled', disabled);
        $('#acr-addon').html(value.length ? '- '+value:'')
                       .css('display', value.length ? 'block':'none');
        selectArea();
    }
    function changeCodeInput(input) {
        var value    = input.val();
        $('#code-addon').html(value.length ? '- '+value:'')
                        .css('display', value.length ? 'block':'none');

    }

    var areas = @php echo json_encode($areas); @endphp;
    function selectArea() {
        var matches = 0;
        $(":input.accounts").each(function(i, val) {
          if ($(this).val() != null) {
            matches++;
          }
        });
        switch (matches) {
            case 1:
                var value = $(":input.accounts").eq(matches-1).val();
                for (var j=0; j<areas.length; j++) {
                    if (areas[j].id == value) {
                        $('#acronym:input').val(areas[j].acronym);
                        var name = areas[j].name;
                        if ($("#type:input").val())
                        name += ' '+$("#type:input option:selected").text();
                        $('#name:input').val(name);
                    }
                }
                break;
            default:
                $('#acronym:input').val(null);
                $('#name:input').val(null);
        }
        return;
    }

    $(document).ready(function() {
        $('.add-to-collection').on('click', function(e) {
            e.preventDefault();
            var container = $('.collection-container');
            var count = container.children('.area').length;
            var proto = container.data('prototype').replace(/__NAME__/g, count);
            container.children('.area').eq(count-1).after(proto);
            selectArea();
        });
        selectArea();
    });

    function rmCollection(btn) {
        btn.closest('.area').remove();
        selectArea();
    }
</script>
@endsection
