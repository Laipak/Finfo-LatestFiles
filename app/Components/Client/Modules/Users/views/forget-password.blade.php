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
.bk-lgn-unme.bk-lgn-fpwd-unme, .bk-lgn-unme-icn.bk-lgn-fpwd-icn
{
    border-bottom: 1px solid #ced4da;
}
.fpwd-link-sec
{
    float: right;
    margin-top: 32.5px;
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
		    	@if(Session::has('message'))
			    		<?php
			    			$alert 		= 'danger';
			    			$message 	= Session::get('message');

			    			if($message['status'] == 1){
			    				$alert = 'success';
			    			}
			    		?>
			    		
		<div class="alert alert-{{$alert}} text-center">               
				            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>              
				                {{ $message['message'] }}				                             
			            </div>
		@endif
			<div class="bk-logo-sec-cvr"><img class="bk-logo-sec" src="../{{$account->company_logo}}"/ style="max-width: 140px;"></div>			
			{!! Form::open(array('route' => 'client.users.do.forget.password')) !!}
			
              <div class="input-group mb-2 mr-sm-2 mb-sm-0 back-lgn-sec-unme{{ $errors->has('email_address') ? ' has-error' : '' }}">
				<div class="input-group-addon bk-lgn-unme-icn bk-lgn-fpwd-icn"><i class="fa fa-envelope-o" aria-hidden="true"></i></div>
				<input type="email" name="email_address" class="form-control bk-lgn-unme bk-lgn-fpwd-unme"
			                   placeholder="Email" required value="{{Input::old('email_address')}}" />
			            	
			  </div>			  
			  
			  <button type="submit" class="btn btn-primary thm-hgt-bgclr">Reset Password</button>	
			  
			   <p class="fpwd-link-sec"><a class="back-lgn-lnk" href="{{URL::to('admin/login')}}">Back to Login</a></p>
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