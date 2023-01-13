@extends('sj_layout')
@section('title'){{ __('Order receptions') }}@endsection
@section('btn-toolbar')@endsection
@section('content')

@include('orders.shared.search', [
        'route'  => route('orders.receptions'),
        'exclude' => ['status'],
    ])

<div class="bg-white border rounded rounded-5 px-2 mb-2">
    <div class="table-responsive">
    <p class="text-muted small my-1">{{ __('Showing :itemsX-:itemsY items from a total of :totalItems', ['itemsX' => $collection->firstItem()?:0, 'itemsY' => $collection->lastItem()?:0, 'totalItems' => $collection->total()]) }}</p>
    <table class="table table-sm align-middle">
        <thead>
        <tr>
            <th class="small" colspan="2" scope="col">{{ __('Order') }} nº
                <a class="{{ request()->get('sortBy') == 'orders.sequence' && request()->get('sort') == 'asc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'orders.sequence', 'sort' => 'asc']) }}">
                    <span data-feather="chevron-up"></span>
                </a>
                <a class="{{ request()->get('sortBy') == 'orders.sequence' && request()->get('sort') == 'desc' ? 'active':'' }}" href="{{ request()->fullUrlWithQuery(['sortBy' => 'orders.sequence', 'sort' => 'desc']) }}">
                    <span data-feather="chevron-down"></span>
                </a>
            </th>
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
                    {{ $order->getSequence() }}
                </td>
                <td>
                <td>{{ $order->getSupplier()->getName() }}</td>
                <td>
                    {{ $order->getAccount()->getSerial() }}
                </td>
                <td>{{ $order->getArea()->getName() }}</td>         
                <td>{{ $order->getAccount()->getTypeName() }}</td>
                <td>
                    <span class="badge {{ $order->getStatusColor() }}">{{ $order->getStatusName() }}</span>
                </td>
                <td>{{ number_format($order->getEstimatedCredit(), 2, ",", ".") }}€</td>              
                <td>
                    <span class="small text-muted">{{ $order->getDate()->format("D, d M Y H:i") }}</span>
                </td>
                <td>{{ $order->getUser()->getShort() }}</td>
                <td>
                {{ Form::open([
                    'route' => ['orders.status', $order->getId()], 
                    'method' => 'post',
                ]) }}
                {{ Form::hidden('status', App\Entities\Order::STATUS_RECEIVED) }}
                    <div class="btn-group btn-group-sm" role="group">
                        
                    @can('update', $order)
                        {{ Form::button('<i class="bx bxs-archive-in"></i>', [
                            'title'   => __('Recepcionar'),
                            'class'   => 'btn btn-light', 
                            'type'    => 'submit',
                            'onclick' => "return confirm('".__('reception.confirm')."')",
                        ]) }}
                    @endcan
                    </div>
                {{ Form::close() }}
                </td>
               
            </tr>
            @endforeach           
            <tr>
                <td class="text-center" colspan="{{ isset($exclude) ? 13 - count($exclude) : 13 }}">{{ $collection->appends(request()->input())->links("pagination::bootstrap-4") }}</td>
            </tr>           
    </table>
    </div>
</div>
  
@endsection
