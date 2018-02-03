@extends($app_template['frontend'])

@section('content')
<div class="panel-grid" id="pg-453-5" >
    <div class="panel-row-style-default panel-row-style nav-one-page" style="background-color: #ecf0f5; padding-top: 100px; padding-bottom: 100px; " overlay="background-color: rgba(255,255,255,0); " id="login">
        <div class="container">
            <div class="panel-grid-cell" id="pgc-453-5-0" >

                <div class="panel-builder widget-builder widget_origin_title panel-first-child" id="panel-453-4-0-0">
                    <div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-dark">
                        <h3  class="align-center">Login</h3><div class="divider colored"></div>
                    </div>
                </div>

                <div class="panel-builder widget-builder widget_origin_spacer" id="panel-453-5-0-1">
                    <div class="origin-widget origin-widget-spacer origin-widget-spacer-simple-blank">
                        <div style="margin-bottom:60px;"></div>
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

                <div class="well well-sm panel-builder widget-builder widget_spot" id="panel-453-5-0-2">
                    <div class="panel-grid" id="pg-905-0" >
                        <div class="panel-row-style-default panel-row-style" >
                            <div class="container col-sm-6 col-sm-offset-3">
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
                </div>

                <div class="panel-builder widget-builder widget_origin_title panel-last-child" id="panel-453-4-0-3">
                    <div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-dark">
                        <h3 class="align-center"></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop