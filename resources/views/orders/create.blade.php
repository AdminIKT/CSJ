@extends('layout')
 
@section('sidebar')
    <a class="btn btn-sm w-100 btn-outline-primary" href="{{ url()->previous() }}">Cancel</a> 
@endsection

@section('content')
   
    {!! form_start($form) !!}
    {!! form_row($form->detail) !!}
    <div class="collection-container" 
         data-prototype="<div>{{ form_row($form->products->prototype()) }}
        <button type='button' onclick='rmCollection(this)'>X</button></div>">
        {!! form_row($form->products) !!}
    </div>
    <button type="button" class="add-to-collection">Add</button>
    {!! form_end($form) !!}

@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.add-to-collection').on('click', function(e) {
                e.preventDefault();
                var container = $('.collection-container');
                var count = container.children().length;
                var proto = container.data('prototype').replace(/__NAME__/g, count);
                container.append(proto);
            });
        });

        function rmCollection(btn) {
            btn.closest('div').remove();
        }
    </script>
@endsection
