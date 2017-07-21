@extends('layouts.app')

@section('content')
    <h1>{{ __('messages.admin') }}</h1>
        @if(count($products) > 0)
            @foreach($products as $product)
                <div class="well" style="float:left; width:33%;">
                    <img class="img-rounded" style="width:100%;" src="{{$product['image']}}"/>
                    <div class="">
                        <h3>{{$product['title']}}</h3>
                        <h5>{{$product['description']}}</h5>
                        <h4>{{$product['price']}}$</h4>
                        <a href="{{route('product.delete', ['id' => $product['ID']])}}" class="btn btn-danger">{{ __('messages.delete') }}</a>
                        <a href="{{route('product.create', ['id' => $product['ID'], 'status' => 'update'])}}" class="btn btn-info">{{ __('messages.edit') }}</a>
                    </div>
                </div>

            @endforeach
        @else
            <p>{{ __('messages.no products') }}</p>
        @endif

@endsection