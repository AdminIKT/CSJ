<!-- FIXME use shared.accounts.table -->

@extends('areas.show')
 
@section('body')

<div class="table-responsive">
  <table class="table table-hover table-sm">
    <thead>
    <tr>
        <th scope="col">{{ __('Acronym') }}</th>
        <th scope="col">{{ __('Type') }}</th>
        <th scope="col">{{ __('Name') }}</th>
    </tr>
    </thead>
    <tbody> 
        @foreach ($collection as $i => $acc)
        <tr>
            <td class="align-middle"><a href="{{ route('accounts.show', ['account' => $acc->getAccount()->getId()]) }}">{{ $acc->getSerial() }}</a></td>
            <td class="align-middle">{{ $acc->getTypeName() }}</td>
            <td class="align-middle">{{ $acc->getName() }}</td>
            <td>
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('accounts.show', ['account' => $acc->getAccount()->getId()]) }}" class="btn btn-outline-secondary">
                        <span data-feather="eye"></span>
                    </a>
                    <a href="{{ route('accounts.edit', ['account' => $acc->getAccount()->getId()]) }}" class="btn btn-outline-secondary disabled">
                        <span data-feather="edit-2"></span>
                    </a>
                    {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary disabled', 'type' => 'submit']) }}
                </div>
            </td>
        </tr>
        @endforeach
    </tbody> 
  </table>
</div>



@endsection

