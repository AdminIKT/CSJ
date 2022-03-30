@extends('suppliers.show')
 
@section('body')
<div class="table-responsive">
    <table class="table table-hover table-sm">
        <thead>
        <tr>
            <th>Detail</th>
            <th>Order</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($entity->getIncidences() as $incidence)
        <tr>
            <td>{{ $incidence->getDetail() }}</td>
            <td>@if ($incidence->getOrder())<a href="{{ route('orders.show', ['order' => $incidence->getOrder()->getId()]) }}">{{ $incidence->getOrder()->getSequence() }}</a>@endif</td>
            <td>{{ $incidence->getCreated()->format("d/m/Y H:i") }}</td>
            <td>
                {{ Form::open([
                    'route' => ['suppliers.incidences.destroy', $entity->getId(), $incidence->getId()], 
                    'method' => 'delete',
                ]) }}
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="{{ route('suppliers.incidences.edit', ['supplier' => $entity->getId(), 'incidence' => $incidence->getId()]) }}" class='btn btn-sm btn-outline-secondary {{request()->is("suppliers/{$entity->getId()}/incidences/{$incidence->getId()}/edit") ? "active" : ""}}'>
                            <span data-feather="edit-2"></span>
                        </a>
                        {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit']) }}
                    </div>
                {{ Form::close() }}
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
@endsection
