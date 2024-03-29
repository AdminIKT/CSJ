<div class="border mb-3 p-3" style="position:relative">
    @if ($index) 
    <input type="button" class="btn btn-smd" onclick="rmCollection(this)" value="X" style="position:absolute; top:0px; right:0px;">
    @endif
    <div class="row">
        <div class="col-md-6 mb-1 has-validations">
            {{ Form::label("products[{$index}][detail]", __('detalle'), ['class' => 'form-label']) }}
            {{ Form::text("products[{$index}][detail]", old("products[{$index}][detail]"), ["class" => "form-control form-control-sm" . ($errors->has("products.{$index}.detail") ? " is-invalid":"")]) }}
            @if ($errors->has("products.{$index}.detail"))
               <div class="invalid-feedback">{!! $errors->first("products.{$index}.detail") !!}</div>
            @endif
        </div>
        <div class="col-md-6 mb-1 has-validations">
            {{ Form::label("products[{$index}][units]", __('cantidad'), ['class' => 'form-label']) }}
            {{ Form::number("products[{$index}][units]", old("products[{$index}][units]"), ["class" => "form-control form-control-sm" . ($errors->has("products.{$index}.units") ? " is-invalid":""), "min" => 0 ]) }}
            @if ($errors->has("products.{$index}.units"))
               <div class="invalid-feedback">{!! $errors->first("products.{$index}.units") !!}</div>
            @endif
            </div>
        </div>
    </div>
</div>
