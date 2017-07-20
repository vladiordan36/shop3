@extends('layouts.app')

@section('content')
    @if(Session::has('logged in'))
        <h1>{{Session::has('update') ? __('messages.edit') : __('messages.add')}}</h1>
        {!! Form::open(['action' => Session::has('update') ? ['ProductsController@update',$post->ID] : 'ProductsController@store', 'method'=> 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('title',__('messages.title'))}}
            {{Form::text('title', Session::has('update') ? $post->title : '',['class' => 'form-control', 'id' => 'title', 'placeholder' => __('messages.title')])}}
            <br />
            {{Form::label('description',__('messages.description'))}}
            {{Form::textarea('description', Session::has('update') ? $post->description : '',['class' => 'form-control', 'id' => 'description', 'placeholder' => __('messages.description')])}}
            <br />
            {{Form::label('price',__('messages.price'))}}
            {{Form::number('price', Session::has('update') ? $post->price : '',['class' => 'form-control', 'id' => 'price', 'placeholder' => __('messages.price')])}}
            <br />
            {{Form::label('image',__('messages.image'))}}
            {{Form::file('image', ['id' => 'image'])}}

            {{Form::hidden('_token',csrf_token())}}

            @if(Session::has('update'))
            {{form::hidden('_method','PUT')}}
            @endif
            <br />
            {{Form::submit(__('messages.submit'), ['class' => 'btn btn-primary', 'id' => 'create-button'])}}
        </div>
        {!! Form::close() !!}
    @else
        <h3 class="alert-danger text-center">{{__('messages.access denied')}}</h3>
    @endif
@endsection
