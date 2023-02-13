<div class="group mb-3 me-3">
    <div class="input-group">
        {{ Form::select("users[{$index}]", 
            [null => __('Selecciona')] + $users, 
            old("users[{$index}]", $id ?? null), 
            [
                'class'=>'users form-select form-select-sm' . ($errors->has("users.{$index}") ? ' is-invalid':''),
            ],
            [null => ['disabled' => true]]
        ) }}
        <input type="button" class="btn btn-sm btn-outline-secondary {{ $index ? '':'disabled' }}" onclick="rmCollection(this)" value="X">
        @if ($errors->has("users.{$index}"))
           <div class="invalid-feedback">{!! $errors->first("users.{$index}") !!}</div>
        @endif
    </div>
</div>
