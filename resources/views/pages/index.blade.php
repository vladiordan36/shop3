@extends('layouts.app')

@section('content')
    <h1>{{ __('messages.products') }}</h1>
    @if(count($products) > 0)
        @foreach($products as $product)
            <div class="well" style="float:left; width:50%;">
                <img style="width:70%;" src="{{$product->image}}"/>
                <p>{{$product->title}}</p>
                <p>{{$product->description}}</p>
                <p>{{$product->price}}$</p>
                <a href="{{route('product.addToCart', ['id' => $product->ID])}}" class="btn btn-default">Add to cart</a>
            </div>

        @endforeach
    @else
        <p>{{ __('messages.no products') }}</p>
    @endif
@endsection