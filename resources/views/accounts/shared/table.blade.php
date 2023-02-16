<div class="table-responsive">
  <p class="text-muted small my-1">{{ __('Showing :itemsX-:itemsY items from a total of :totalItems', ['itemsX' => $collection->firstItem()?:0, 'itemsY' => $collection->lastItem()?:0, 'totalItems' => $collection->total()]) }}</p>
  <table class="table table-sm align-middle">
    <thead>
    <tr>
        <th scope="col">{{ __('acronimo') }}
            <a class="{{ request()->get('sortBy') == 'account.acronym' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.acronym', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'account.acronym' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.acronym', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('tipo') }}</th>
        <th scope="col">{{ __('Areas') }}</th>
        <th scope="col">{{ __('Real credit') }}
            <a class="{{ request()->get('sortBy') == 'account.credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.credit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'account.credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.credit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('Compromised credit') }}
            <a class="{{ request()->get('sortBy') == 'account.compromisedCredit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.compromisedCredit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'account.compromisedCredit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.compromisedCredit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('Available credit') }}
        </th>
        <th scope="col">{{ __('Created') }}
            <a class="{{ request()->get('sortBy') == 'account.created' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.created', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'account.created' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'account.created', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th scope="col">{{ __('Actions') }}</th>
    </tr>
    </thead>
    <tbody> 
        @foreach ($collection as $i => $entity)
        <tr>
            <td>
                <span class="me-1 cbg {{ $entity->getStatusColor() }}" title="{{ $entity->getStatusName() }}"></span>
                <a href="{{ route('accounts.show', ['account' => $entity->getId()]) }}" data-bs-toggle="tooltip">{{ $entity->getSerial() }}</a>
                <span class="small text-muted">{{ $entity->getName() }}</span>
            </td>
            <td>{{ $entity->getTypeName() }}</td>
            <td>{{ implode(", ", $entity->getAreas()->map(function ($e) { return $e->getName(); })->toArray()) }}</td>
            <td>{{ number_format($entity->getCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getCompromisedCredit(), 2, ",", ".") }}€</td>
            <td>{{ number_format($entity->getAvailableCredit(), 2, ",", ".") }}€</td>
            <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
            <td>
            {{ Form::open([
                'route' => ['accounts.destroy', $entity->getId()], 
                'method' => 'delete',
            ]) }}
            <div class="btn-group btn-group-sm" role="group">
                @can('viewany', \App\Entities\Account::class)
                    <span class="clip">{{ __("Copied") }}</span>
                    @if ($entity->getSubaccounts()->count() === 1)
                    <button type="button" class="btn btn-light" title="{{ __('Copy to clipboard') }}"
                            data-clip="C#{{ $entity->getSerial() }}" onclick="copyToClipboard($(this))">
                        <span class="bx bxs-copy"></span>
                    </button>
                    @else
                    <button type="button" 
                        id="{{$entity->getId()}}-clip-btn" 
                        class="btn btn-light dropdown-toggle" 
                        title="{{ __('Copy to clipboard') }}"
                        data-bs-toggle="dropdown"
                        >
                        <span class="bx bxs-copy"></span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="{{$entity->getId()}}-clip-btn">
                        @foreach ($entity->getSubaccounts() as $acc)
                        <li>
                            <a href="#" data-clip="C#{{ $acc->getSerial() }}" onclick="copyToClipboard($(this))" class="dropdown-item">
                                {{ $acc->getArea() }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                @endcan
                @can('view', $entity)
                <a href="{{ route('accounts.show', ['account' => $entity->getId()]) }}" class="btn btn-light" title="{{ __('View') }}">
                    <i class="bx bxs-show"></i>
                </a>
                @endcan
                @can('update', $entity)
                <a href="{{ route('accounts.edit', ['account' => $entity->getId()]) }}" class="btn btn-light" title="{{ __('Edit') }}">
                    <i class="bx bxs-pencil"></i>
                </a>
                @endcan
                @can('delete', $entity)
                {{ Form::button('<i class="bx bxs-trash-alt"></i>', [
                    'title'   => __('Delete'),
                    'class'   => 'btn btn-light', 
                    'type'    => 'submit',
                    'onclick' => "return confirm('".__('delete.confirm')."')",
                ]) }}
                @endcan
            </div>
            {{ Form::close() }}
            </td>
        </tr>
        @endforeach
        </tbody> 
    </table> 
    @if ($pagination ?? '')
    <div class="col-12">
        {{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}
    </div>
    @endif
</div>
