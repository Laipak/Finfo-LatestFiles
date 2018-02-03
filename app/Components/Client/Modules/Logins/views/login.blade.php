@extends('resources.views.templates.client.template2.frontendhome')


@section('content')

<?php 
$account = \Session::get('account_data');
?>
<style>
#snavvy-menu{
display:none;
}

footer
{
    
  border-top: none!important;
}

body{
    
     overflow: hidden!important;
}

footer p {
    font-size: 14px;
}
</style>
        
<div class="back-lgn-bg back-lgn-sec">	
		<div  style="margin: 0 auto; text-align:center;" >
        @if (count($errors) > 0)
          <div class="alert alert-danger text-center" style="margin:0 auto;display: inline-block;">               
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>               
              @foreach ($errors->all() as $error)
                {{ $error }}<br>
              @endforeach               
          </div>
        @endif
</div>
		<div class="back-lgn-bg-lay thm-bdrclr" style="min-height: 370px;">
		    
			<div class="bk-logo-sec-cvr"><img class="bk-logo-sec" src="../{{$account->company_logo}}"/ style="max-width: 140px;"></div>			
			{!! Form::open(array('url'=>'/do-login', 'class'=>'form-horizontal')) !!}
		
              <div class="input-group mb-2 mr-sm-2 mb-sm-0 back-lgn-sec-unme">
				<div class="input-group-addon bk-lgn-unme-icn"><i class="fa fa-envelope-o" aria-hidden="true"></i></div>
				<input type="email" class="form-control bk-lgn-unme" id="bk-lgn-email" placeholder="Email Address" name="email" value="{{ old('email') }}" autofocus>				
			  </div>			  
			  <div class="input-group mb-2 mr-sm-2 mb-sm-0 back-lgn-sec-pwd">
				<div class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></div>
				<input type="password" class="form-control bk-lgn-pwd" id="password" name="password" placeholder="Password">
				<div class="input-group-addon"><a href="{{ url('users/forgot-password') }}" class="back-lgn-lnk">Forgot?</a></div>
			  </div>			  
			  <button type="submit" class="btn btn-primary thm-hgt-bgclr">Login</button>			  
			{!! Form::close() !!}			
		</div>
		
		<footer class="ftr-bl-lgn">
		  <div class="container-fluid">
			<div class="row">
				<div class="col-12 col-sm-12 col-md-6 col-lg-6">
					<p class="cpy-txt">Copyright © 2017 <a href="#">{{$setting->company_name}}</a>. All rights reserved.</p>
				</div>
				<div class="col-12 col-sm-12 col-md-6 col-lg-6">	
					<p class="ftr-bk-lgn-rt">Powered by &nbsp;&nbsp;&nbsp; <img src="https://wizwerx.info/img/finfo/imgs/finfo-logo.png"/></p>
				</div>
			</div>
		  </div>      
    </footer>
		
		
	</div>
	

@stop
