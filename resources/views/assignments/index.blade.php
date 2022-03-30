@extends('new_layout')
@section('title'){{ __('Assignments') }}@endsection
@section('btn-toolbar')
    <a href="{{ route('assignments.create') }}" class="btn btn-sm btn-outline-secondary">
    <span data-feather="plus"></span> New
    </a>
@endsection
@section('content')

<div class="table-responsive">
  <table class="table table-hover table-sm align-middle">
      <thead>
      <tr>
          <th scope="col">{{ __('Area') }}</th>
          <th scope="col">{{ __('Type') }}</th>
          <th scope="col">{{ __('Credit') }}</th>
          <th scope="col">{{ __('Detail') }}</th>
          <th scope="col">{{ __('Created') }}</th>
          <th scope="col">{{ __('Actions') }}</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($collection as $i => $entity)
      <tr>
          <td><a href="{{ route('areas.show', ['area' => $entity->getArea()->getId()]) }}">{{ $entity->getArea()->getName() }}-{{ $entity->getArea()->getType() }}</a></td>
          <td>{{ $entity->getTypeName() }}</td>
          <td>{{ number_format($entity->getCredit(), 2, ",", ".") }} €</td>
          <td>{{ $entity->getDetail() }}</td>
          <td>{{ $entity->getCreated()->format("d/m/Y H:i") }}</td>
          <td>
          {{ Form::open([
              'route' => ['assignments.destroy', $entity->getId()], 
              'method' => 'delete',
          ]) }}
              <div class="btn-group btn-group-sm" role="group">
                  <!--<a href="{{ route('assignments.show', ['assignment' => $entity->getId()]) }}" class="btn btn-outline-secondary">
                    <span data-feather="eye"></span>
                  </a>
                  <a href="{{ route('assignments.edit', ['assignment' => $entity->getId()]) }}" class="btn btn-outline-secondary">
                    <span data-feather="edit-2"></span>
                  </a>-->
                  {{ Form::button('<span data-feather="trash"></span>', ['class' => 'btn btn-outline-secondary', 'type' => 'submit']) }}
              </div>
          {{ Form::close() }}
          </td>
      </tr>
      @endforeach
      <tr>
          <td colspan="6" class="text-center">{{ $collection->links("pagination::bootstrap-4") }}</td>
      </tr>
      </tbody>
    </table> 
</div>
@endsection
