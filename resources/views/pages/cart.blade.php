@extends('layouts.app')

@section('content')
    <h1>{{__('messages.cart')}}</h1>
    @if(Session::has('cart'))
        @foreach($products as $product)
            <div class="well">
                <img style="width:20%;" src="{{$product['item']['image']}}"/>
                <h3>{{$product['item']['title']}}</h3>
                <h5>{{$product['item']['description']}}</h5>
                <h4>{{$product['quantity']}} * {{$product['item']['price']}}$ = {{$product['quantity']*$product['item']['price']}}$</h4>

                {!! Form::open(['action' => ['PagesController@updateQuantity',$product['item']['ID']], 'method'=> 'GET']) !!}
                    {{Form::label('quantity',__('messages.quantity'))}}
                    {{Form::number('quantity', '',['class' => 'form', 'placeholder' => __('messages.quantity')])}}
                    {{Form::submit(__('messages.save'), ['class' => 'btn btn-primary'])}}
                {!! Form::close() !!}

                <a href="{{route('product.removeFromCart', ['id' => $product['item']['ID']])}}" class="btn btn-default">{{__('messages.remove')}}</a>
            </div>
        @endforeach
        <div class="well"><h3>Total: {{$total}} $</h3></div>

        {!! Form::open(['action' => 'PagesController@checkout', 'method'=> 'GET']) !!}
        {{Form::label('quantity',__('messages.email'))}}
        {{Form::text('quantity', '',['class' => 'form', 'placeholder' => __('messages.email')])}}
        {{Form::submit(__('messages.checkout'), ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}

    @else
        <h3>{{__('messages.cart empty')}}</h3>
        <a href="/" class="btn btn-default">{{__('messages.back')}}</a>
    @endif
@endsection