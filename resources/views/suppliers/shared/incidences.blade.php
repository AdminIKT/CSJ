<div class="table-responsive">
    <table class="table table-sm align-middle">
        <thead>
            <tr>
                @if (!(isset($exclude) && in_array('orders', $exclude)))
                <th>{{ __('Order') }} nº</th>
                @endif
                <th>{{ __('Detail') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('User') }}</th>
                <th>{{ __('Created') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($entity->getIncidences() as $incidence)
            <tr>
                @if (!(isset($exclude) && in_array('orders', $exclude)))
                <td>@if ($incidence->getOrder())<a href="{{ route('orders.show', ['order' => $incidence->getOrder()->getId()]) }}">{{ $incidence->getOrder()->getSequence() }}</a>@endif</td>
                @endif
                <td>{{ $incidence->getDetail() }}</td>
                <td><span class="badge {{ $incidence->getStatusColor() }}">{{ $incidence->getStatusName() }}</span></td>
                <td>{{ $incidence->getUser()->getName() }}</td>
                <td>{{ $incidence->getCreated()->format("d/m/Y H:i") }}</td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        @can('update', $incidence)
                        <a href="{{ route('suppliers.incidences.close', ['supplier' => $incidence->getSupplier()->getId(), 'incidence' => $incidence->getId(), 'destination' => request()->url()]) }}" class='btn btn-sm btn-outline-secondary {{request()->is("suppliers/{$incidence->getSupplier()->getId()}/incidences/{$incidence->getId()}/close") ? "active" : ""}}' data-bs-toggle="tooltip" title="close">
                            <span data-feather="x-circle"></span>
                        </a>
                        @endcan
                        @can('update', $incidence)
                        <a href="{{ route('suppliers.incidences.edit', ['supplier' => $incidence->getSupplier()->getId(), 'incidence' => $incidence->getId(), 'destination' => request()->url()]) }}" class='btn btn-sm btn-outline-secondary {{request()->is("suppliers/{$incidence->getSupplier()->getId()}/incidences/{$incidence->getId()}/edit") ? "active" : ""}}'>
                            <span data-feather="edit-2"></span>
                        </a>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
        <!--
        <tr>
            <td colspan="4" align="center">
                <a href="{{ route('suppliers.incidences.create', ['supplier' => $entity->getId()]) }}" class='btn btn-sm btn-outline-secondary {{request()->is("suppliers/{$entity->getId()}/incidences/create") ? "active" : "" }}'>
                    <span data-feather="bell"></span> {{ __('New incidence') }}
                </a>
            </td>
        </tr>
        -->
        </tbody>
    </table>
</div>
