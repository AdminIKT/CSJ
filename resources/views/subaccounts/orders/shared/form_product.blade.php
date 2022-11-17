<div class="product border mb-1 p-2" style="position:relative">
    <a href="#" class="bx bx-sm bxs-x-square" onclick="rmCollection(this)" style="position:absolute; top:0px; right:0px;"></a>
    <div class="row">
        {{ Form::hidden("products[{$index}][id]", old("products[{$index}][id]", $product ? $product['id'] : null)) }}
        <div class="col-md-6 mb-1 has-validations">
            {{ Form::label("products[{$index}][detail]", __('detalle'), ['class' => 'form-label']) }}
            {{ Form::text("products[{$index}][detail]", old("products[{$index}][detail]", $product ? $product['detail'] : null), ["class" => "form-control form-control-sm" . ($errors->has("products.{$index}.detail") ? " is-invalid":"")]) }}
            @if ($errors->has("products.{$index}.detail"))
               <div class="invalid-feedback">{!! $errors->first("products.{$index}.detail") !!}</div>
            @endif
        </div>
        <div class="col-md-6 mb-1 has-validations">
            {{ Form::label("products[{$index}][units]", __('cantidad'), ['class' => 'form-label']) }}
            {{ Form::number("products[{$index}][units]", old("products[{$index}][units]", $product ? $product['units'] : null), ["class" => "form-control form-control-sm" . ($errors->has("products.{$index}.units") ? " is-invalid":""), "min" => 0 ]) }}
            @if ($errors->has("products.{$index}.units"))
               <div class="invalid-feedback">{!! $errors->first("products.{$index}.units") !!}</div>
            @endif
        </div>
    </div>
</div>
