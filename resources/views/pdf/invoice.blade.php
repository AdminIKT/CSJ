@extends('pdf_layout')
@section('title')
{{ $entity->getSequence() }}
@endsection
@section('content')      
      <h1 style="text-align: center; color:#52bdc3;" font-family="font/Roboto-Regular.ttf">{{__('Order') }} {{ $entity->getSequence() }}</h1>
      <br/>

    <!--  <p style="font-family:Roboto"><span style="font-weight:bold;">{{__('Order') }}: </span>{{$entity->getSequence()}}</p>
      <p style="font-family:Roboto"><span style="font-weight:bold;">{{__('Area') }}: </span>{{$entity->getArea()}}</p>
      <p style="font-family:Roboto"><span style="font-weight:bold;">{{__('Account') }}: </span>{{$entity->getAccount()->getSerial()}}</p>
      <p style="font-family:Roboto"><span style="font-weight:bold;">{{__('Date') }}: </span>{{ Carbon\Carbon::parse($entity->getCreated())->diffForHumans() }}</p>
      <p style="font-family:Roboto"><span style="font-weight:bold;">{{__('Supplier') }}: </span>{{$entity->getSupplier()->getName()}}</p>
      <p style="font-family:Roboto"><span style="font-weight:bold;">{{__('Estimated') }}: </span>{{ $entity->getEstimatedCredit() ? number_format($entity->getEstimatedCredit(), 2, ",", ".").'€' : '-'}}</p>
      <p style="font-family:Roboto"><span style="font-weight:bold;">{{__('Detail') }}: </span>{{ $entity->getDetail()}}</p>-->
         
      <table width="100%">
            <tr style="margin-bottom:20px;">
                  <td height="40px" style="font-weight:bold;">{{__('Order') }}:</td>
                  <td>{{$entity->getSequence()}}</td>
                  <td style="font-weight:bold;">{{__('Area') }}: </td>
                  <td>{{$entity->getArea()}}</td>
            </tr>
            <tr>
                  <td height="40px" style="font-weight:bold;">{{__('Date') }}: </td>
                  <td>{{ $entity->getCreated()->format("d/m/Y") }}</td>
                  <td style="font-weight:bold;">{{__('Account') }}:</td>
                  <td>{{$entity->getAccount()->getSerial()}}</td>
            </tr>
            <tr>
                  <td height="40px" style="font-weight:bold;">{{__('Supplier') }}: </td>
                  <td>{{$entity->getSupplier()->getName()}}</td>
                  <td style="font-weight:bold;">{{__('Estimated') }}:</td>
                  <td>{{ $entity->getEstimatedCredit() ? number_format($entity->getEstimatedCredit(), 2, ",", ".").'€' : '-'}}</td>
            </tr>
            <tr>
                  <td height="40px" style="font-weight:bold;">{{__('Detail') }}: </td>
                  <td colspan="3">{{ $entity->getDetail()}}</td>
            </tr>            
            @if (count($entity->getProducts())) 
                <tr>
                    <td height="80px" colspan="4" style="font-weight:bold;">{{ __('elementos') }}:</td>                   
                </tr>
                @foreach ($entity->getProducts() as $product)
                <tr>
                    <td colspan="2">{{ $product->getDetail() }}</td>
                    <td colspan="2">{{ $product->getUnits() }}</td>                   
                </tr>
                @endforeach
            @endif      
      </table>
@endsection

