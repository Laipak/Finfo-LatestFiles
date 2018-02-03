@extends($app_template['frontend'])

@section('content')
<div class="panel-grid" id="pg-453-5" >
    <div class="panel-row-style-default panel-row-style nav-one-page" style="background-color: #ecf0f5; padding-top: 100px; padding-bottom: 100px; " overlay="background-color: rgba(255,255,255,0); " id="login">
        <div class="container">
            <div class="panel-grid-cell" id="pgc-453-5-0" >

                <div class="panel-builder widget-builder widget_origin_title panel-first-child" id="panel-453-4-0-0">
                    <div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-dark">
                        <h3  class="align-center">Reset Password</h3><div class="divider colored"></div>
                    </div>
                </div>

                <div class="panel-builder widget-builder widget_origin_spacer" id="panel-453-5-0-1">
                    <div class="origin-widget origin-widget-spacer origin-widget-spacer-simple-blank">
                        <div style="margin-bottom:60px;"></div>
                    </div>
                </div>
                @if(Session::has('message'))
                    <?php
                        $alert      = 'danger';
                        $message    = Session::get('message');

                        if($message['status'] == 1){
                            $alert = 'success';
                        }
                    ?>
                    <div class="alert alert-{{$alert}} text-center">               
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>              
                            {{ $message['message'] }}                                            
                    </div>
                @endif
                <div class="well well-sm panel-builder widget-builder widget_spot" id="panel-453-5-0-2">
                    <div class="panel-grid" id="pg-905-0" >
                        <div class="panel-row-style-default panel-row-style" >
                            <div class="container col-sm-6 col-sm-offset-3">
                                {!! Form::open(array('route' => 'finfo.admin.do.forget.password', 'name'=>'sentMessage', 'id'=>'contactForm', 'novalidate'=>'')) !!}  
                                    <div class="row">
                                    <div class="form-group ">
                                      <label class="col-lg-3 control-label">Email Address</label>
                                      <div class="col-lg-12">
                                        <input name="email_address" id="email" class="form-control" value="{{Input::old('email_address')}}" type="email" placeholder="Email Address">
                                        <p class="help-block text-danger"></p>
                                            {!! $errors->first('email_address', '<span class="help-block erorr">:message</span>') !!}
                                      </div>
                                    </div>   
                                    </div>          
                                    <br>
                                    <div id="success"></div>
                                    <div class="row">
                                        <div class="form-group col-xs-12">
                                            <button type="submit" class="btn btn-success btn-lg">Send</button>
                                        </div>
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
@section('seo')
    <title>Finfo | Forget password</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('style')
    <style type="text/css">
    .erorr{
        color: red !important;
    }
    .btn-success{
        padding: 10px 15px;
    }
@stop 