
<div class="contact border mb-3 p-3" style="position:relative">
    @if ($index) 
    <input type="button" class="btn btn-smd" onclick="rmCollection(this)" value="X" style="position:absolute; top:0px; right:0px;">
    @endif
    <div class="row">
        <div class="col-md-6 mb-1 has-validations">
            <label for="contacts[{{$index}}][name]">{{__('nombre')}}</label>
            {{ Form::text("contacts[{$index}][name]", null, ["class" => "form-control form-control-sm" . ($errors->has("contacts.{$index}.name") ? " is-invalid":"")]) }}
            @if ($errors->has("contacts.{$index}.name"))
               <div class="invalid-feedback">{!! $errors->first("contacts.{$index}.name") !!}</div>
            @endif
        </div>
        <div class="col-md-6 mb-1 has-validations">
            <label for="contacts[{{$index}}][position]">{{__('Position')}}</label>
            {{ Form::text("contacts[{$index}][position]", null, ["class" => "form-control form-control-sm" . ($errors->has("contacts.{$index}.position") ? " is-invalid":"")]) }}
            @if ($errors->has("contacts.{$index}.position"))
               <div class="invalid-feedback">{!! $errors->first("contacts.{$index}.position") !!}</div>
            @endif
        </div>
        <div class="col-md-6 mb-1 has-validations">
            <label for="contacts[{{$index}}][email]">{{__('Email')}}</label>
            {{ Form::text("contacts[{$index}][email]", null, ["class" => "form-control form-control-sm" . ($errors->has("contacts.{$index}.email") ? " is-invalid":"")]) }}
            @if ($errors->has("contacts.{$index}.email"))
               <div class="invalid-feedback">{!! $errors->first("contacts.{$index}.email") !!}</div>
            @endif
        </div>
        <div class="col-md-6 mb-1 has-validations">
            <label for="contacts[{{$index}}][phone]">{{__('Phone')}}</label>
            {{ Form::text("contacts[{$index}][phone]", null, ["class" => "form-control form-control-sm" . ($errors->has("contacts.{$index}.phone") ? " is-invalid":"")]) }}
            @if ($errors->has("contacts.{$index}.phone"))
               <div class="invalid-feedback">{!! $errors->first("contacts.{$index}.phone") !!}</div>
            @endif
        </div>
    </div>
</div>
