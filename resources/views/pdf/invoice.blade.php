@extends('pdf_layout')
@section('title')
{{ $entity->getSequence() }}
@endsection
@section('content')      
      <h1 style="text-align: center; color:#52bdc3;" font-family="font/Roboto-Regular.ttf"><u>{{__('OrderRequest') }}</u></h1>           
      <table style="border-collapse:collapse;margin-top:30px;" width="100%">
            <tr style="margin-bottom:20px;">
                  <td height="30px" style="font-weight:bold;width:18%;">{{__('Order nº') }}:</td>
                  <td style="width:1%;white-space:nowrap;border:1;border-style:solid;border-color:#52bdc3;padding:5px;">{{$entity->getSequence()}}</td>             
                  <td colspan="3">&nbsp;</td>    
            </tr>
            <tr style="margin-bottom:20px;">                  
                  <td height="30px" style="font-weight:bold;">{{__('Area') }}: </td>
                  <td colspan="4">{{$entity->getArea()}}</td>
            </tr>
            <tr style="margin-bottom:20px;">                  
                  <td height="30px" style="font-weight:bold;">{{__('Solicitante') }}: </td>
                  <td colspan="4">{{$entity->getUser()->getName()}}</td>
            </tr>
            <tr>
                  <td height="30px" style="font-weight:bold;">{{__('Date') }}: </td>
                  <td colspan="2">{{ $entity->getCreated()->format("d/m/Y") }}</td>
                  <td style="font-weight:bold;width:20%;text-align:right;padding-right:10px;">{{__('Type') }}:</td>
                  <td>{{$entity->getAccount()->getTypeName()}}</td>
            </tr>
            <tr>
                  <td height="30px" style="font-weight:bold;">{{__('Supplier') }}: </td>
                  <td colspan="4">{{$entity->getSupplier()->getName()}}</td>                  
            </tr>
            @foreach ($entity->getSupplier()->getContacts() as $contact)
            <tr>
                  <td height="30px" style="font-weight:bold;">{{__('Contact') }}: </td>
                  <td colspan="4">{{$contact->getName()}}</td>
            </tr>
            <tr>
                  <td height="30px"  style="font-weight:bold;">{{__('Email') }}:</td>
                  <td colspan="2"> {{$contact->getEmail()}}</td>
                  <td style="font-weight:bold;width:20%;text-align:right;padding-right:10px;">{{__('Phone') }}:</td>
                  <td> {{$contact->getPhone()}}</td>                  
            </tr>
            @endforeach
            <tr>
                  <td height="30px" style="font-weight:bold;">{{ __('Receive in') }}: </td>
                  <td colspan="4">{{ $entity->getReceiveInName() }}</td>                  
            </tr>           
            <tr>
                  <td height="30px" style="font-weight:bold;">{{__('Detail') }}: </td>
                  <td colspan="4">{{ $entity->getDetail()}}</td>
            </tr>            
            @if (count($entity->getProducts())) 
                <tr>
                    <td height="30px" colspan="5" style="font-weight:bold;padding-bottom:10px;padding-top:10px;"><br/>{{ __('elementos') }}:</td>                   
                </tr>
                <tr>
                    <td style="border:1;border-style:solid;padding:5px;" colspan="3"><b>{{__('Detail')}}</b></td>
                    <td style="border:1;border-style:solid;padding:5px;" colspan="2"><b>{{__('cantidad')}}</b></td>                   
                </tr>
                @foreach ($entity->getProducts() as $product)
                <tr>
                    <td style="border:1;border-style:solid;padding:5px;" colspan="3">{{ $product->getDetail() }}</td>
                    <td style="border:1;border-style:solid;padding:5px;" colspan="2">{{ $product->getUnits() }}</td>                   
                </tr>
                @endforeach
            @endif    
      </table>
      <div style="font-size:10px;margin-top:40px;">
            <p><u>{{ __('order.pdf.notas0') }}</u></p>
            <p style="margin: 0 auto;">{{ __('order.pdf.notas1') }}</p>
            <p style="margin: 0 auto;">{{ __('order.pdf.notas2') }}</p>
            <p style="margin: 0 auto;">{{ __('order.pdf.notas3') }}</p>
            <p style="margin: 0 auto;">{{ __('order.pdf.notas4') }}</p>
            <p style="margin: 0 auto;">{{ __('order.pdf.notas5') }}</p>
      </div>
@endsection

