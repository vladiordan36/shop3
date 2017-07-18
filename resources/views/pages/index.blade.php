@extends('layouts.app')

@section('content')
    <h1>{{ __('messages.products') }}</h1>
    @if(count($products) > 0)
        @foreach($products as $product)
            <div class="well" style="float:left; width:33%; height:10%;">
                <img class="img-rounded" style="width:100%;" src="{{$product->image}}"/>
                <h3>{{$product->title}}</h3>
                <h5>{{$product->description}}</h5>
                <h4>{{$product->price}}$</h4>
                <a href="{{route('product.addToCart', ['id' => $product->ID])}}" class="btn btn-info">{{ __('messages.add to cart') }}</a>
            </div>

        @endforeach
    @else
        <p>{{ __('messages.no products') }}</p>
    @endif
@endsection