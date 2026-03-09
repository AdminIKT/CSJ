<div class="table-responsive">
  @if(is_object($collection) && method_exists($collection, 'total'))
  <p class="text-muted small my-1">{{ __('Showing :itemsX-:itemsY items from a total of :totalItems', ['itemsX' => $collection->firstItem()?:0, 'itemsY' => $collection->lastItem()?:0, 'totalItems' => $collection->total()]) }}</p>
  @else
  <p class="text-muted small my-1">{{ __('Showing :total items', ['total' => is_countable($collection) ? count($collection) : 0]) }}</p>
  @endif
  <table class="table table-sm align-middle">
    <thead>
    <tr>
        <th scope="col" class="small">{{ __('Avatar') }}</th>
        <th scope="col" class="small">{{ __('Email') }}</th>
        <th scope="col" class="small">{{ __('Name') }}</th>
        <th scope="col" class="small">{{ __('Roles') }}</th>
        <th scope="col" class="small">{{ __('Accounts') }}</th>
        <th scope="col" class="small">{{ __('Created') }}</th>
        <th scope="col" class="small">{{ __('Last login') }}</th>
        <th scope="col" class="small">{{ __('Actions') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($collection as $entity)
    <tr>
        <td>
            <span class="me-1 cbg {{ $entity->getStatusColor() }}" title="{{ $entity->getStatusName() }}"></span>
            @if ($entity->getAvatar()) <img src="{{ $entity->getAvatar() }}" height="25" width="25" class="rounded-circle"/> @endif
        </td>
        <td>{{ $entity->getEmail() }}</td>
        <td>{{ ucwords(strtolower($entity->getName())) }}</td>
        <td>{{ implode(", ", $entity->getRoles()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
        <td>{{ implode(", ", $entity->getAccounts()->map(function ($e) { return "{$e->getSerial()} ({$e->getType()})"; })->toArray()) }}</td>
        <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
        <td>@if ($entity->getLastLogin()) {{ $entity->getLastLogin()->format("d/m/Y H:i") }} @endif</td>
        <td class="m-0">
            {{ Form::open([
                'route' => ['users.destroy', $entity->getId()], 
                'method' => 'delete',
            ]) }}

            <div class="btn-group btn-group-sm">
                <a href="{{route('users.show', ['user' => $entity->getId()])}}" class="btn btn-light">
                    <span class="bx bxs-show"></span>
                </a>
                <a href="{{route('users.edit', ['user' => $entity->getId()])}}" class="btn btn-light">
                    <span class="bx bxs-pencil"></span>
                </a>
                {{ Form::button('<i class="bx bxs-trash-alt"></i>', [
                    'class'   => 'btn btn-light', 
                    'type'    => 'submit',
                    'onclick' => "return confirm('".__('delete.confirm')."')",
                ]) }}
            </div>
        {{ Form::close() }}
        </td>
    </tr>
    @endforeach

    @if (($pagination ?? false) && is_object($collection) && method_exists($collection, 'total'))
    <tr>
        <td class="text-center" colspan="8">{{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}</td>
    </tr>
    @endif
    </tbody>
  </table>
</div>
