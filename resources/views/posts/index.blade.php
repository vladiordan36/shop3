@extends('layouts.app')

@section('content')
    <h1>{{ __('messages.admin') }}</h1>
    @if(!Auth::guest())
        @if(count($products) > 0)
            @foreach($products as $product)
                <div class="well" style="float:left; width:50%;">
                    <img style="width:70%;" src="{{$product->image}}"/>
                    <h3>{{$product->title}}</h3>
                    <h5>{{$product->description}}</h5>
                    <h4>{{$product->price}}$</h4>
                    <a href="{{route('product.delete', ['id' => $product->ID])}}" class="btn btn-default">{{ __('messages.delete') }}</a>
                    <a href="{{route('product.edit', ['id' => $product->ID])}}" class="btn btn-default">{{ __('messages.edit') }}</a>
                </div>

            @endforeach
        @else
            <p>{{ __('messages.no products') }}</p>
        @endif
    @else
        <h3 class="alert-danger text-center">{{ __('messages.access denied') }}</h3>
    @endif
@endsection