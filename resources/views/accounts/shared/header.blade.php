@if ($entity->getFilesId(\App\Entities\Account\DriveFile::TYPE_ESTIMATE))
    <div class="input-group input-group-sm m-1">
        <span class="input-group-text">{{ __('Presupuestos') }}</span>
        <button id="filesBtn" class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Presupuestos') }}">
            <img src="/img/google/drive-doc.png" alt="{{ __('Drive storage') }}" title="{{ __('Drive storage') }}" height="20px">
        </button>
        <ul class="dropdown-menu" aria-labelledby="filesBtn">
            @foreach ($entity->getFiles(\App\Entities\Account\DriveFile::TYPE_ESTIMATE) as $file)
                <li>
                    <a href="{{ $file->getFileUrl() }}" class="dropdown-item" target="_blank">{{ $file->getName() }}</a>
                </li>
            @endforeach
            @if ($entity->getFiles(\App\Entities\Account\DriveFile::TYPE_ESTIMATE)->count())
                <li><hr class="dropdown-divider"></li>
            @endif
            <li>
                <a href="{{ $entity->getFilesUrl(\App\Entities\Account\DriveFile::TYPE_ESTIMATE) }}" class="dropdown-item" target="_blank">{{ __('All') }}</a>
            </li>
        </ul>
    </div>
@endif
@if ($entity->getFilesId(\App\Entities\Account\DriveFile::TYPE_INVOICE))
    <div class="input-group input-group-sm m-1">
        <span class="input-group-text">{{ __('Invoices') }}</span>
        <button id="filesBtn" class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('Invoices') }}">
            <img src="/img/google/drive-double-check.png" alt="{{ __('Drive storage') }}" title="{{ __('Drive storage') }}" height="20px">
        </button>
        <ul class="dropdown-menu" aria-labelledby="filesBtn">
            @foreach ($entity->getFiles(\App\Entities\Account\DriveFile::TYPE_INVOICE) as $file)
                <li>
                    <a href="{{ $file->getFileUrl() }}" class="dropdown-item" target="_blank">{{ $file->getName() }}</a>
                </li>
            @endforeach
            @if ($entity->getFiles(\App\Entities\Account\DriveFile::TYPE_INVOICE)->count())
                <li><hr class="dropdown-divider"></li>
            @endif
            <li>
                <a href="{{ $entity->getFilesUrl(\App\Entities\Account\DriveFile::TYPE_INVOICE) }}" class="dropdown-item" target="_blank">{{ __('All') }}</a>
            </li>
        </ul>
    </div>
@endif
@if ($entity->getSubaccounts()->count() === 1)
    <div class="btn-group btn-group-sm m-1">
        @can('viewany', \App\Entities\Account::class)
        <button type="button" class="btn btn-outline-secondary" title="{{ __('Copy to clipboard') }}"
                data-clip="C#{{ $entity->getSerial() }}" onclick="copyToClipboard($(this))">
            <span class="clip">{{ __("Copied") }}</span>
            <span class="bx bxs-copy"></span>
        </button>
        @endcan
        @can('view', $entity)
        <a href="{{ route('subaccounts.orders.create', ['subaccount' => $entity->getSubaccounts()->first()->getId()]) }}" class="btn btn-outline-secondary">
            <i class="bx bx-xs bxs-file"></i> {{ __('New order') }} 
        </a>
        @endcan
        @can('update', $entity)
        <button id="movementBtn" class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-xs bx-dollar"></i> {{ __('New movement') }} 
        </button>
        <ul class="dropdown-menu" aria-labelledby="movementBtn">
            <li>
                <a href="{{ route('subaccounts.assignments.create', ['subaccount' => $entity->getSubaccounts()->first()->getId()]) }}" class="dropdown-item">+<span class="bx bx-xs bx-dollar"></span> {{ __('New assignment') }}</a>
            </li>
            <li>
                <a href="{{ route('subaccounts.charges.create', ['subaccount' => $entity->getSubaccounts()->first()->getId()]) }}" class="dropdown-item">-<span class="bx bx-xs bx-dollar"></span> {{ __('New charge') }}</a>
            </li>
        </ul>
        @endcan
    </div>
@endif
{{ Form::open([
    'route' => ['accounts.destroy', $entity->getId()], 
    'method' => 'delete',
]) }}
    <div class="btn-group btn-group-sm m-1" role="group">
        @if (!request()->is("accounts/{$entity->getId()}*"))
            <a href="{{ route('accounts.show', ['account' => $entity->getId()]) }}" class="btn btn-outline-secondary" title="{{ __('View') }}">
                <span class="bx bxs-show"></span>
            </a>
        @endif
        @can('update', $entity)
        <a href="{{ route('accounts.edit', ['account' => $entity->getId()]) }}" class="btn btn-outline-secondary" title="{{ __('Edit') }}">
            <span class="bx bxs-pencil"></span>
        </a>
        @endcan
        @can('delete', $entity)
        {{ Form::button('<i class="bx bxs-trash-alt"></i>', [
            'class'   => 'btn btn-outline-secondary', 
            'type'    => 'submit',
            'title'   => __('Delete'),
            'onclick' => "return confirm('".__('delete.confirm')."')",
        ]) }}
        @endcan
    </div>
{{ Form::close() }}

