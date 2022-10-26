<div class="area me-3">
    <div class="input-group">
        {{ Form::select("accounts[{$index}]", 
            [null => __('Selecciona')] + $accounts, 
            old("accounts[{$index}]", null), 
            [
                'class'=>'accounts form-select form-select-sm' . ($errors->has("accounts.{$index}") ? ' is-invalid':''),
                'onchange' => 'selectArea()'
            ],
            [null => ['disabled' => true]]
        ) }}
        <input type="button" class="btn btn-sm btn-outline-secondary {{ $index ? '':'disabled' }}" onclick="rmCollection(this)" value="X">
        @if ($errors->has("accounts.{$index}"))
           <div class="invalid-feedback">{!! $errors->first("accounts.{$index}") !!}</div>
        @endif
    </div>
</div>
