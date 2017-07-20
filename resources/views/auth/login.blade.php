@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{__('messages.login')}}</h2></div>
                <div class="panel-body">
                    <div class="form-group">
                        {!!  Form::open(['action' => 'PagesController@login', 'method' => 'POST'])!!}
                        <div class="form-group">
                            {{Form::label('email',__('messages.email'))}}
                            {{Form::text('email', '',['class' => 'form', 'placeholder' => __('messages.email')])}}
                        </div>
                        <div class="form-group">
                            {{Form::label('password',__('messages.psw'))}}
                            {{Form::password('password', '',['class' => 'form', 'placeholder' => __('messages.psw')])}}
                        </div>
                        {{Form::submit(__('messages.login'), ['class' => 'btn btn-primary'])}}

                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
