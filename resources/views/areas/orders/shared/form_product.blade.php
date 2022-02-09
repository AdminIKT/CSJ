<div class="border mb-3 p-3" style="position:relative">
    @if ($index) 
    <input type="button" class="btn btn-smd" onclick="rmCollection(this)" value="X" style="position:absolute; top:0px; right:0px;">
    @endif
    <div class="row">
        <div class="col-md-4 mb-1 has-validations">
            <label for="products[{{$index}}][supplier]">Supplier</label>
            {{ Form::select("products[{$index}][supplier]", [null => 'Select one'] + $suppliers, null, ["class" => "form-select form-select-sm" . ($errors->has("products.{$index}.supplier") ? " is-invalid":"")], [null => ["disabled" => true]]) }}
            @if ($errors->has("products.{$index}.supplier"))
               <div class="invalid-feedback">{!! $errors->first("products.{$index}.supplier") !!}</div>
            @endif
        </div>
        <div class="col-md-4 mb-1 has-validations">
            <label for="products[{{$index}}][detail]">Detail</label>
            {{ Form::text("products[{$index}][detail]", null, ["class" => "form-control form-control-sm" . ($errors->has("products.{$index}.detail") ? " is-invalid":"")]) }}
            @if ($errors->has("products.{$index}.detail"))
               <div class="invalid-feedback">{!! $errors->first("products.{$index}.detail") !!}</div>
            @endif
        </div>
        <div class="col-md-4 mb-1 has-validations">
            <label for="products[{{$index}}][credit]">Credit</label>
            <div class="input-group input-group-sm">
                {{ Form::number("products[{$index}][credit]", null, ["class" => "form-control" . ($errors->has("products.{$index}.credit") ? " is-invalid":""), "step" => "0.01", "min" => 0 ]) }}
                <span class="input-group-text">€</span>
                @if ($errors->has("products.{$index}.credit"))
                   <div class="invalid-feedback">{!! $errors->first("products.{$index}.credit") !!}</div>
                @endif
            </div>
        </div>
    </div>
</div>
