@extends($app_template['client.frontend'])

@section('content')
@if ($active_theme == "default")
<div class="row title" id="media-access">
    <div class="col-md-12">
     Media Access
    </div>
</div>
 <section class="content" id="media-access">
    <div class="container">
    	<div class="row">    		
    	</div>
    </div>
    <div class="content">
    	<div class="row message">
    		<div class="col-md-12 text-center">
                    <h2>THANK YOU</h2>
                    @if(session()->has('data')) 
                        <p> You had been registered with username, {{session('data')}}<i>.</i></p>
                        <p> We are checking your account, then we will send you the active email<i>.</i></p>
                    @endif
    		</div>
    	</div>
    </div>
</section>
@else
  <h2>THANK YOU</h2>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
       @if(session()->has('data')) 
            <p> You had been registered with username, {{session('data')}}<i>.</i></p>
            <p> We are checking your account, then we will send you the active email<i>.</i></p>
        @endif
    </div>
  </div>
@endif
@stop
@section('seo')
    <title>{{ucfirst(Session::get('company_name'))}} | Media Access</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('style')
@if ($active_theme == "default")
{!! Html::style('css/package/general-style.css') !!}
  <style type="text/css">

    h3 {
      font-weight: bold;
      color: #e53840;
    }
    
    h2 {
      font-weight: bold;
      color: #ffcd20;
    }

    p {
      font-size: 18px;
      color: #272c30;
    }

    .message {
      background-color: #FFF;
      padding: 65px;
    }

  </style>
  @endif
@stop