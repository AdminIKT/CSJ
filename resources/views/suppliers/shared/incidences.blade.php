<div class="table-responsive">
    <table class="table table-hover table-sm">
        <thead>
        <tr>
            @if (!(isset($exclude) && in_array('orders', $exclude)))
            <th>{{ __('Order') }} nº</th>
            @endif
            <th>{{ __('Detail') }}</th>
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
            <td>{{ $incidence->getUser()->getName() }}</td>
            <td>{{ $incidence->getCreated()->format("d/m/Y H:i") }}</td>
            <td>
            {!! Form::open([
                'route' => ['suppliers.incidences.destroy', $incidence->getSupplier()->getId(), $incidence->getId()], 
                'method' => 'delete',
            ]) !!}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('suppliers.incidences.edit', ['supplier' => $entity instanceof App\Entities\Order ? $entity->getSupplier()->getId() : $entity->getId(), 'incidence' => $incidence->getId()]) }}" class='btn btn-sm btn-outline-secondary {{request()->is("suppliers/{$entity->getId()}/incidences/{$incidence->getId()}/edit") ? "active" : ""}}'>
                        <span data-feather="edit-2"></span>
                    </a>
                    {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit']) }}
                </div>
            {!! Form::close() !!}
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
        <tbody>
    </table>
</div>
