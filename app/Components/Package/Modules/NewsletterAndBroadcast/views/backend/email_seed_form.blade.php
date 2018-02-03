@extends($app_template['client.backend'])
@section('title')
Newsletter & Broadcast
@stop
@section('content')
    <section class="content" id="financial-annual-report">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Settings</lable>
            </div>
        </div>
@if (isset($users) && !empty($users))
 @foreach($users as $users)
 
<?php

$send = $users->sender_email;
$reply = $users->reply_name;
$name = $users->reply_email;

?>


 @endforeach
 @else
 <?php

$send = '';
$reply = '';
$name = '';

?>
 
@endif 
<div class="box">		
		{!! Form::open(array('route' => 'package.admin.newsletter-broadcast.config', 'id' => 'frm-reports', 'files'=> true)) !!}
		<div class="box-body set-box-body">
			<div class="row"><div class="clearfix" style="padding:5px;"></div></div>
			
			<div class="row">
				<div class="col-md-7">
					<h5>Sender's Email</h5>          
					<input type="text" id="slider_description" name="sender_email" placeholder="example@mail.com" class="form-control" value="{{$send}}">        
				</div>
			</div>
				<div class="row">
				<div class="col-md-7">
					<h5>Reply-to-Name</h5>          
					<input type="text" id="slider_description" name="reply_to_name" placeholder="" class="form-control" value="{{$reply}}">        
				</div>
			</div>
				<div class="row">
				<div class="col-md-7">
					<h5>Reply-to-Email</h5>          
					<input type="text" id="slider_description" name="reply_to_email" placeholder="example@mail.com" class="form-control" value="{{$name}}">        
				</div>
			</div>
			
		  
			<div class="form-group" style="margin-top:30px;">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
            </div>
			<div class="row"><div class="clearfix" style="padding:17px;"></div></div>
	</div>
	
	</div>
        <!--div class="row">
            {!! Form::open(array('route' => 'package.admin.newsletter-broadcast.email-seed-list.save', 'id' => 'frm-reports', 'files'=> true)) !!}
            <div class="col-md-6">

               <div class="form-group">
                        {!! Form::label('name', 'Name') !!}
                        {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="form-group">
                        {!! Form::label('email', 'Email') !!}
                        {!! Form::text('email', Input::old('email'), ['class' => 'form-control', 'placeholder' => 'Email']) !!}
                        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                </div>

                <div class="form-group" style="margin-top:30px;">
                    {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
                    <a href="{{route('package.admin.newsletter-broadcast.email-seed-list')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
                </div>
            </div>
            
           </div-->
           
      <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Tester Email(s)</lable>
            </div>
        </div>      
  <div class="box">   
  <div class="box-body set-box-body">
      <div class="row"><div class="clearfix" style="padding:5px;"></div></div>
     <div class="row">
				<div class="col-md-7">
      
				</div>
			</div>
       <div id="list_all">
           
           

       </div>
    
           
           
           
           <div class="row">
               
              <div class="col-md-6">

               <div class="form-group">
                        
                        {!! Form::text('email_id', Input::old('email_id'),['class' => 'form-control email_id', 'placeholder' => 'example@mail.com']) !!}
                        
                         
                       
                </div>
                
                
               
            </div>
            
            <div class="col-md-1">
                
                 <button type="button" class="btn btn-primary" id="str_email"><i class="fa fa-plus"></i></button>
                
                
            </div>
            
            
            <span id="error"></span>
                
            
            {!! Form::close() !!}
        </div>
        </div>
        </div>
        
        
    </section>
@stop
@section('style')
    {!! Html::style('css/package/financial-annual-report.css') !!}
@stop

@section('script')
<script type="text/javascript">

    function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

    $('.active').removeClass('active');
    $('.news_brod').addClass('active');
    $('.news_brod_email_seed_list').addClass('active');
    
    
    $(document).ready(function(){
    
            $.ajax({
			            url: baseUrl + '/admin/newsletter-broadcast/email-seed-list/mailslisting',
			            type: "GET",
			            success: function(data) {
			            
			                   $('#list_all').html(data);
			                    $('.email_id').val('');
			            
			      
			            },
			        });
    
    
});

    
    
    
    $('#str_email').click(function(){
        
            var mail_id = $('.email_id').val();
            
        
         if (validateEmail(mail_id)) {
            
              $("#error").html("");
              $('.email_id').attr('style','border-color:none');
           
              $.ajax({
                  
			            url: baseUrl + '/admin/newsletter-broadcast/email-seed-list/addmails',
			            type: "POST",
			            data: {"mail_id":mail_id,"_token":"{{csrf_token()}}"},
			            success: function(data) {
			                
			                if(data == 0){
			                    
			                       $("#error").html("Sorry, Given Email is Already Exist");
                                  $("#error").css("color", "red");
                                  $('.email_id').val('');
			                    
			                }else{
			                
			                    $('#list_all').html(data);
			                    $('.email_id').val('');
			                    
			                }
			               
			                
			                
			            }
			            
		    });
		    
         }else{
                 
                $("#error").html(" Please enter the correct email");
                $("#error").css("color", "red");
                $('.email_id').attr('style','border:2px solid #ed1717');
                return false;
                 
                 
                 
             }
    });
    
    

      
      function remove_mail(){
        
       
        
            var id = $('#mail-id').val();
            
             if(confirm("Are you sure you want to remove this?"))
            {
            
             $.ajax({
                  
			            url: baseUrl + '/admin/newsletter-broadcast/email-seed-list/deletemail',
			            type: "POST",
			            data: {"id":id,"_token":"{{csrf_token()}}"},
			            success: function(res) {
			                    $('#list_all').html(res);
			                    $('.email_id').val('');
			                    
			             
			                
			                
			            }
			            
		    });
        
            }else{
                return false;
            } 
      }
      
</script>
@stop
