@extends('layouts.app')

@section('content')
    @if(!Auth::guest())
        <h1>{{Session::has('update') ? __('messages.edit') : __('messages.add')}}</h1>
        {!! Form::open(['action' => Session::has('update') ? ['PostsController@update',$post->ID] : 'PostsController@store', 'method'=> 'POST', 'enctype' => 'multipart/form-data']) !!}
        <div class="form-group">
            {{Form::label('title',__('messages.title'))}}
            {{Form::text('title', Session::has('update') ? $post->title : '',['class' => 'form-control', 'placeholder' => __('messages.title')])}}

            {{Form::label('description',__('messages.description'))}}
            {{Form::textarea('description', Session::has('update') ? $post->description : '',['class' => 'form-control', 'placeholder' => __('messages.description')])}}

            {{Form::label('price',__('messages.price'))}}
            {{Form::text('price', Session::has('update') ? $post->price : '',['class' => 'form-control', 'placeholder' => __('messages.price')])}}

            {{Form::label('image',__('messages.image'))}}
            {{Form::file('image')}}

            @if(Session::has('update'))
            {{form::hidden('_method','PUT')}}
            @endif

            {{Form::submit(__('messages.submit'), ['class' => 'btn btn-primary'])}}
        </div>
        {!! Form::close() !!}
    @else
        <h3 class="alert-danger text-center">{{__('messages.access denied')}}</h3>
    @endif
@endsection