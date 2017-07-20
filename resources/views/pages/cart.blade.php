@extends('layouts.app')

@section('content')
    <h1>{{__('messages.cart')}}</h1>
    @if(Session::has('cart'))
        @foreach($products as $product)
            <div class="well" style="float:left; width:33%;">
                <img class="img-rounded" style="width:100%;" src="{{$product['image']}}"/>
                <h3>{{$product['title']}}</h3>
                <h5>{{$product['description']}}</h5>
                <h4>{{$cart[$product['ID']]}} * {{$product['price']}}$ = {{$cart[$product['ID']]*$product['price']}}$</h4>
                <div style="float:left;">
                    {!! Form::open(['action' => ['PagesController@updateQuantity',$product['ID']], 'method'=> 'GET']) !!}
                        {{Form::label('quantity',__('messages.quantity'))}}
                        {{Form::number('quantity', '',['class' => 'form', 'min' => 0, 'max' => 100])}}
                        {{Form::submit(__('messages.save'), ['class' => 'btn btn-primary'])}}
                    {!! Form::close() !!}
                </div>
                <div>
                    <a href="{{route('product.removeFromCart', ['id' => $product['ID']])}}" class="btn btn-default">{{__('messages.remove')}}</a>
                </div>
            </div>
        @endforeach

        <div class="well" style="clear:both;" >
            <h3>{{__('messages.total').$total}}$</h3>
            <br />
            {!! Form::open(['action' => 'PagesController@checkout', 'method'=> 'GET']) !!}
            {{Form::label('email',__('messages.email'))}}
            {{Form::text('email', '',['class' => 'form', 'placeholder' => __('messages.email')])}}
            {{Form::submit(__('messages.checkout'), ['class' => 'btn btn-primary'])}}
            {!! Form::close() !!}
        </div>

    @else
        <h3>{{__('messages.cart empty')}}</h3>
        <a href="/index" class="btn btn-default">{{__('messages.back')}}</a>
    @endif
@endsection