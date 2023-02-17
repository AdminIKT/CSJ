<div class="table-responsive">
  <p class="text-muted small my-1">{{ __('Showing :itemsX-:itemsY items from a total of :totalItems', ['itemsX' => $collection->firstItem()?:0, 'itemsY' => $collection->lastItem()?:0, 'totalItems' => $collection->total()]) }}</p>
  <table class="table table-sm align-middle">
    <thead>
    <tr>
        <th class="small" scope="col" colspan="1">{{ __('Order') }} nº
            <a class="{{ request()->get('sortBy') == 'orders.sequence' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'orders.sequence', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'orders.sequence' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'orders.sequence', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th class="small" title="Presupuesto">{{ ('Pre.') }}</th>
        <th class="small" title="Factura">{{ ('Fac.') }}</th>
        @if (!(isset($exclude) && in_array('suppliers', $exclude)))
        <th class="small" scope="col">{{ __('Supplier') }}</th>
        @endif
        @if (!(isset($exclude) && in_array('accounts', $exclude)))
        <th class="small" scope="col">{{ __('Account') }}</th>
        @endif
        @if (!(isset($exclude) && in_array('areas', $exclude)))
        <th class="small" scope="col">{{ __('Area') }}</th>
        @endif
        @if (!(isset($exclude) && in_array('types', $exclude)))
        <th class="small" scope="col">{{ __('Type') }}</th>
        @endif
        <th class="small" scope="col">{{ __('Status') }}</th>
        <th class="small" scope="col">{{ __('Predicted') }}
            <a class="{{ request()->get('sortBy') == 'orders.estimatedCredit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'orders.estimatedCredit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'orders.estimatedCredit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'orders.estimatedCredit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th class="small" scope="col">{{ __('Credit') }}
            <a class="{{ request()->get('sortBy') == 'orders.credit' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'orders.credit', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'orders.credit' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'orders.credit', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        <th class="small" scope="col">{{ __('Date') }}
            <a class="{{ request()->get('sortBy') == 'orders.date' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'orders.date', 'sort' => 'asc']) }}">
                <span data-feather="chevron-up"></span>
            </a>
            <a class="{{ request()->get('sortBy') == 'orders.date' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'orders.date', 'sort' => 'desc']) }}">
                <span data-feather="chevron-down"></span>
            </a>
        </th>
        @if (!(isset($exclude) && in_array('users', $exclude)))
        <th class="small" scope="col">{{ __('User') }}</th>
        @endif
        @if (!(isset($exclude) && in_array('actions', $exclude)))
        <th class="small" scope="col">{{ __('Actions') }}</th>
        @endif
    </tr>
    </thead>
    <tbody> 
        @php 
            $totalEstimated = $totalCredit = 0; 
        @endphp
        @foreach ($collection as $i => $order)
        @php
            $trEstimated = $trCredit = 1;
            $totalEstimated += $order->getEstimatedCredit();
            $totalCredit += $order->getCredit();
        @endphp
        <tr>
            <td>
                <a href="{{ route('orders.show', ['order' => $order->getId()]) }}">{{ $order->getSequence() }}</a>
            </td>
            <td>
               @if ($order->getEstimateFileId())
               <a href="{{ $order->getEstimateFileUrl() }}" target="_blank" title="{{ __('Google storage') }}">
                   <img src="/img/google/drive-doc.png" alt="{{ __('Drive storage') }}" height="16px">
               </a>
               @endif
            </td>
            <td>
               @if ($order->getInvoiceFileId())
               <a href="{{ $order->getInvoiceFileUrl() }}" target="_blank" title="{{ __('Google storage') }}">
                   <img src="{{ $order->getInvoiceIcon() }}" alt="{{ __('Drive storage') }}" height="16px">
               </a>
               @endif
            </td>
            @if (!(isset($exclude) && in_array('suppliers', $exclude)))
            @php $trEstimated++ @endphp
            <td><a href="{{ route('suppliers.show', ['supplier' => $order->getSupplier()->getId()]) }}" class="small text-muted">{{ $order->getSupplier()->getName() }}</a></td>
            @endif
            @if (!(isset($exclude) && in_array('accounts', $exclude)))
            @php $trEstimated++ @endphp
            <td>
                <a href="{{ route('accounts.show', ['account' => $order->getAccount()->getId()]) }}" data-bs-toggle="tooltip" title="{{ $order->getAccount()->getName() }}">{{ $order->getAccount()->getSerial() }}</a>
            </td>
            @endif
            @if (!(isset($exclude) && in_array('areas', $exclude)))
            @php $trEstimated++ @endphp
            <td><a href="{{ route('areas.show', ['area' => $order->getArea()->getId()]) }}">{{ $order->getArea()->getName() }}</a></td>
            @endif
            @if (!(isset($exclude) && in_array('types', $exclude)))
            @php $trEstimated++ @endphp
            <td>{{ $order->getAccount()->getTypeName() }}</td>
            @endif
            <!--<td>{{ $order->getProducts()->count() }}</td>-->
            <td>
                <span class="badge {{ $order->getStatusColor() }}">{{ $order->getStatusName() }}</span>
            </td>
            <td>{{ number_format($order->getEstimatedCredit(), 2, ",", ".") }}€</td>
            <td>@if ($order->getCredit()) {{ number_format($order->getCredit(), 2, ",", ".") }}€ @endif</td>
            <td>
                <span class="small text-muted">{{ $order->getDate()->format("D, d M Y") }}</span>
            </td>
            @if (!(isset($exclude) && in_array('users', $exclude)))
            @php $trCredit++ @endphp
            <td>{{ $order->getUser()->getShort() }}</td>
            @endif
            @if (!(isset($exclude) && in_array('actions', $exclude)))
            @php $trCredit++ @endphp
            <td>
            {{ Form::open([
                'route' => ['orders.destroy', $order->getId()], 
                'method' => 'delete',
            ]) }}
                <div class="btn-group btn-group-sm" role="group">
                @can('viewany', \App\Entities\Order::class)
                    <button type="button" class="btn btn-light" title="{{ __('Copy to clipboard') }}"
                            data-clip="P#{{ $order->getSequence() }}" onclick="copyToClipboard($(this))">
                        <span class="clip">{{ __("Copied") }}</span>
                        <span class="bx bxs-copy"></span>
                    </button>
                @endcan
                @can('view', $order)
                    <a class="btn btn-light" 
                        title="{{ __('Pdf') }}"
                        href="{{ route('orders.invoices.create', ['order' => $order->getId()]) }}" 
                        target="_blank">
                        <span class="bx bx-xs bxs-file-pdf bx-tada-hover"></span>
                    </a>
                @endcan
                @can('view', $order)
                    <a href="{{ route('orders.show', ['order' => $order->getId()]) }}" class="btn btn-light" title="{{ __('View') }}">
                        <span class="bx bxs-show"></span>
                    </a>
                @endcan
                @can('update', $order)
                    <a href="{{ route('orders.edit', ['order' => $order->getId(), 'destination' => route('orders.index')]) }}" class="btn btn-light" title="{{ __('Edit') }}">
                        <span class="bx bxs-pencil"></span>
                    </a>
                @endcan
                @can('delete', $order)
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
            @endif
        </tr>
        @endforeach
        @if ($pagination ?? '')
        <tr>
            <td class="text-center" colspan="{{ isset($exclude) ? 13 - count($exclude) : 13 }}">{{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}</td>
        </tr>
        @elseif ($collection->total())
        <tr style="background: #DDDDDD;">
            <td colspan="{{ $trEstimated }}">{{ __('Total') }}:</td>
            <td>{{ number_format($totalEstimated, 2, ",", ".") }}€</td>
            <td colspan="2"></td>
            <td>{{ number_format($totalCredit, 2, ",", ".") }}€</td>
            <td colspan="{{ $trCredit }}"></td>
        </tr>
        @endif
  </table>
</div>

