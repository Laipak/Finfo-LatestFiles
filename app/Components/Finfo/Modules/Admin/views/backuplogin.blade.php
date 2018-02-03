@extends($app_template['frontend'])

@section('content')

<section>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>Login</h2>
                    <hr class="star-primary">
                </div>
            </div>

            @if (count($errors) > 0)
            <div class="alert alert-danger text-center">
                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
            </div>
            @endif

            @if(Session::has('message'))
            <div class="alert alert-danger text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{Session::get('message')}}
            </div>
            @endif
            @if(Session::has('global'))
            <div class="alert alert-success text-center">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{Session::get('global')}}
            </div>
            @endif

            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                
                    {!! Form::open(array('url'=>'admin/do-login', 'name'=>'sentMessage', 'id'=>'contactForm', 'novalidate'=>'')) !!}
                      
                        <div class="row control-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Email Address</label>
                                <input name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="Email Address" required data-validation-required-message="Please enter your email address.">

                            </div>
                        </div>
                        
                        <div class="row control-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password" required data-validation-required-message="Please enter password.">

                            </div>
                        </div>
                        <br>
                        <div id="success"></div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <button type="submit" class="btn btn-success btn-lg">Login</button>
                            </div>
                        </div>
                        <div class="row">
                          <p class='col-xs-12'><a href="{{ url('admin/forgot-password') }}">Forgot password?</a></p>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@stop
@section('seo')
    <title>Finfo | Login</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
