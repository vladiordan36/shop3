@extends('layouts.app')

@section('content')
    @if(Session::has('logged in'))
        <h1>{{$status == 'update' ? __('messages.edit') : __('messages.add')}}</h1>
        {!! Form::open(['action' => ['ProductsController@store','id' => $status == 'update' ? $product['ID'] : '0', 'status' => $status] , 'method'=> 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('title',__('messages.title'))}}
            {{Form::text('title', $status == 'update' ? $product->title : '',['class' => 'form-control', 'id' => 'title', 'placeholder' => __('messages.title')])}}
            <br />
            {{Form::label('description',__('messages.description'))}}
            {{Form::textarea('description', $status == 'update' ? $product->description : '',['class' => 'form-control', 'id' => 'description', 'placeholder' => __('messages.description')])}}
            <br />
            {{Form::label('price',__('messages.price'))}}
            {{Form::number('price', $status == 'update' ? $product->price : '',['class' => 'form-control', 'id' => 'price', 'placeholder' => __('messages.price')])}}
            <br />
            {{Form::label('image',__('messages.image'))}}
            {{Form::file('image', ['id' => 'image'])}}

            {{Form::hidden('_token',csrf_token())}}

            <br />
            {{Form::submit(__('messages.submit'), ['class' => 'btn btn-primary', 'id' => 'create-button'])}}
        </div>
        {!! Form::close() !!}
    @else
        <h3 class="alert-danger text-center">{{__('messages.access denied')}}</h3>
    @endif
@endsection
