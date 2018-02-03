@extends($app_template['backend'])

@section('content')
<section class="content" id="list-user">
    <div class="row head-search">
        <div class="col-sm-6">
            <h2 style="margin:0;">Edit Company Information</h2>
        </div>
        <div class="col-sm-6">
            <div class="pull-right">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">     
        <br><br>    

          <div class="box">
            @if(Session::has('success'))
    		        <div class="alert alert-success">
    		        	<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    		            {{ Session::get('success') }}
    		        </div>
    		    @endif         
                <div id="box-user" class="box-body">

                {!! Form::open(array('route' => 'finfo.admin.clients.update', 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'frm_company_edit')) !!}

                	<div class="form-group">
    						    <label class="control-label col-sm-2" for="">Company Logo:</label>
    						    <div class="col-sm-6"> 
    						        <div class="thumbnail col-md-3">
                          @if($data['company_logo'] == '')
                              <img src="/img/nologo.jpg" class="img-responsive">
                          @else
                              <img src="/{{$data['company_logo']}}" class="img-responsive">
                          @endif
                      </div>
    						    </div>
    						</div>

                <div class="form-group {{ $errors->has('company_name') ? ' has-error' : '' }}">
  						    <label class="control-label col-sm-2" for="">Company Name:</label>
  						    <div class="col-sm-6"> 
  						      <input name="company_name" type="text" class="form-control" id="" placeholder="Enter Company Name" value="{{$data['company_name']}}">
  						      {!! $errors->first('company_name', '<span class="help-block">:message</span>') !!}
  						    </div>
  						</div>

						<div class="form-group {{ $errors->has('finfo_account_name') ? ' has-error' : '' }}">
						    <label class="control-label col-sm-2" for="">Finfo Account Name:</label>               
						    <div class="col-sm-6"> 
						      <input name="finfo_account_name" type="text" class="form-control" id="" placeholder="Enter Company Name" value="{{$data['finfo_account_name']}}" @if($data['approved_by']==0) disabled @endif>
						      {!! $errors->first('finfo_account_name', '<span class="help-block">:message</span>') !!}
						    </div>                
						</div>

            <div class="form-group {{ $errors->has('finfo_account_name1') ? ' has-error' : '' }}">
                <label class="control-label col-sm-2" for="">Preferred Account Name 1:</label>               
                <div class="col-sm-6"> 
                  <input name="finfo_account_name1" type="text" class="form-control" id="account_name1" placeholder="Enter Company Name" value="{{$data['finfo_account_name1']}}">
                  {!! $errors->first('finfo_account_name1', '<span class="help-block">:message</span>') !!}
                </div>                
            </div>

            <div class="form-group {{ $errors->has('finfo_account_name2') ? ' has-error' : '' }}">
                <label class="control-label col-sm-2" for="">Preferred Account Name 2:</label>               
                <div class="col-sm-6"> 
                  <input name="finfo_account_name2" type="text" class="form-control" id="account_name2" placeholder="Enter Company Name" value="{{$data['finfo_account_name2']}}">
                  {!! $errors->first('finfo_account_name2', '<span class="help-block">:message</span>') !!}
                </div>                
            </div>

						<div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
						    <label class="control-label col-sm-2" for="">Company Phone Number:</label>
						    <div class="col-sm-6"> 
                  <input type="number" name="phone" type="tel" class="form-control" id="" placeholder="Enter Company Phone Number" value="{{$data['phone']}}" minlength="6" maxlength="20">
						      {!! $errors->first('phone', '<span class="help-block">:message</span>') !!}
						    </div>
						</div>

                		<div class="form-group {{ $errors->has('address') ? ' has-error' : '' }}">
						    <label class="control-label col-sm-2" for="">Company Address:</label>
						    <div class="col-sm-6"> 
						      <input name="address" type="text" class="form-control" id="" placeholder="Enter Company Address" value="{{$data['address']}}">
						      {!! $errors->first('address', '<span class="help-block">:message</span>') !!}
						    </div>
						</div>

                		<div class="form-group {{ $errors->has('email_address') ? ' has-error' : '' }}">
						    <label class="control-label col-sm-2" for="">Company Email Address:</label>
						    <div class="col-sm-6"> 
						      <input name="email_address" type="email" class="form-control" id="" placeholder="Enter Company Email Address" value="{{$data['email_address']}}">
						      {!! $errors->first('email_address', '<span class="help-block">:message</span>') !!}
						    </div>
						</div>

						<div class="form-group {{ $errors->has('website') ? ' has-error' : '' }}">
						    <label class="control-label col-sm-2" for="">Company Website:</label>
						    <div class="col-sm-6"> 
						      <input name="website" type="text" class="form-control" id="" placeholder="Enter Company Website" value="{{$data['website']}}">
						      {!! $errors->first('website', '<span class="help-block">:message</span>') !!}
						    </div>
						</div>

                		<div class="form-group {{ $errors->has('established') ? ' has-error' : '' }}">
						    <label class="control-label col-sm-2" for="">Company Established on:</label>
						    <div class="col-sm-6">
						      <input name="established" type="text" class="form-control" id="established" placeholder="Enter Company Established date" >
						      {!! $errors->first('established', '<span class="help-block">:message</span>') !!}
						    </div>
						</div>

                		<div class="form-group {{ $errors->has('number_of_employee') ? ' has-error' : '' }}">
						    <label class="control-label col-sm-2" for="">Number of Employee:</label>
						    <div class="col-sm-6"> 
						      <input name="number_of_employee" type="number" class="form-control" id="" placeholder="Enter Only Number" value="{{$data['number_of_employee']>0?$data['number_of_employee']:''}}">
						      {!! $errors->first('number_of_employee', '<span class="help-block">:message</span>') !!}
						    </div>
					    </div>

					  <div class="form-group">
					    <label class="control-label col-sm-2" for="">Common Stock:</label>
					    <div class="col-sm-6"> 
					      <input name="common_stock" type="number" class="form-control" id="" placeholder="Enter Common Stock" value="{{$data['common_stock']>0?$data['common_stock']:''}}">
					    </div>
					  </div>

                       <div class="form-group">
					    <label class="control-label col-sm-2" for="">Main Business Activities:</label>
					    <div class="col-sm-6"> 
					      <input name="main_business_activities" type="text" class="form-control" id="" placeholder="Enter Main Business Activities" value="{{$data['main_business_activities']}}">
					    </div>
					   </div>

					  <div class="form-group"> 
					    <div class="col-sm-offset-2 col-sm-6">
					      <button type="submit" class="btn btn-primary">Save</button>
					    </div>
					  </div>
					  <input type="hidden" name="id" id="client-id" value="{{$data['id']}}">
				{!!Form::close()!!}
				
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade"  id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      {!!Form::open(array('url' => route('finfo.admin.clients.approve'), 'method' => 'POST'))!!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
          <p>Which company account's name will be use for this account?</p>
        <div class="radio">
          <label class="option1">
            <input type="radio" name="option" value="">
            <span></span>
          </label>
        </div>
        <div class="radio">
          <label class="option2">
            <input type="radio" name="option" value="">
            <span></span>
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="option" value="" id="other">
            <span>Other</span>
            <input type="text" name="otherText" id="otherText" value="" placeholder="Account Name" style="display:none;">
          </label>
        </div>
        <textarea class="form-control" placeholder="Your Message"></textarea>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Approve</button>
        <input type="hidden" id="h_id" name="h_id" value="">
      </div>
        {!!Form::close()!!}
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<div class="modal fade"  id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
     {!!Form::open(array('url' => route('finfo.admin.clients.reject'), 'method' => 'POST'))!!}
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Reject Company</h4>
      </div>
      <div class="modal-body">
        <p>Give them a reason why you reject them?</p>
        <textarea name="message" class="form-control {{ $errors->has('company_name') ? ' has-error' : '' }}" placeholder="Your Message"></textarea>
        <p class="help-block text-danger"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Reject</button>
        <input type="hidden" id="r_id" name="r_id" value="">
      </div>
    {!!Form::close()!!}
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>
@stop

@section('style')
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
    <style>
        label#phone-error, .error{
            color: red;
            font-weight: normal;
        }
        .has-error{
          color: red;
          opacity: 1 !important;
          position: static !important;
        }
    </style>
@stop


@section('script')
	{!! Html::script('js/moment.min.js') !!}
	{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
        {!! Html::script('js/jquery.validate.min.js') !!}
        
<script type="text/javascript">
  $.ajaxSetup({
      headers:{
          'X-CSRF-TOKEN':'{!! csrf_token() !!}'
      }
  });

  jQuery.validator.addMethod("noSpace", function(value, element) { 
    return value.indexOf(" ") < 0 || value.indexOf(/([!,%,&,@,#,$,^,*,?,_,~])/) < 0 ; 
  }, "Cannot use space in this field.");

  $.validator.addMethod("regex", function(value, element, regexp) {
      if (value.indexOf(".") !== -1) {
          return false;
      }
      var check = false;
      var re = new RegExp(regexp);
      return this.optional(element) || re.test(value);
  }," No special Characters allowed here! ");

  $.validator.addMethod('lowercase', function(value) {
        return value.match(/^[^A-Z]+$/);
    }, 'No Capital letter allowed here.');

  jQuery.validator.addMethod("notEqualTo",  
      function(value, element, param) {  
           return this.optional(element) || value!=$(param).val(); }, 
           "Preferred Account Name 1 and Preferred Account Name 2 should be different value."
  );

	$('#established').datetimepicker({
        format: 'DD MMMM, YYYY',
        @if($data['established_at'] != "0000-00-00")
            defaultDate: "{{$data['established_at'] == 0 ? $data['created_at'] : $data['established_at']}}"
        @endif
        
    }).val();

    $("#frm_company_edit").validate({
        errorClass: 'has-error',
        highlight: function (element, errorClass) { 
            $(element).closest("div.form-group").addClass(errorClass);
        },
        unhighlight: function (element, errorClass) { 
            $(element).closest("div.form-group").removeClass(errorClass); 
        },
        rules: {
          'finfo_account_name': {
            required: true,
            noSpace: true,
            lowercase: true,
            maxlength: 23,
            minlength: 6,
            regex: /^[A-Za-z0-9\.-]+$/,
            remote: {
              url: '/admin/clients/check/domain_with_id',
              type: "post",
              data: {
                  id:  function() {
                      return $( "#client-id" ).val();
                    },
                }
            }
          },
          'finfo_account_name1': {
            required: true,
            noSpace: true,
            lowercase: true,
            maxlength: 23,
            minlength: 6,
            regex: /^[A-Za-z0-9\.-]+$/,
            notEqualTo: "#account_name2",
            remote: {
              url: '/admin/clients/check/domain_with_id',
              type: "post",
              data: {
                  id:  function() {
                      return $( "#client-id" ).val();
                    },
                }
            }
          },
          'finfo_account_name2': {
            required: true,
            noSpace: true,
            lowercase: true,
            maxlength: 23,
            minlength: 6,
            regex: /^[A-Za-z0-9\.-]+$/,
            notEqualTo: "#account_name1",
            remote: {
              url: '/admin/clients/check/domain_with_id',
              type: "post",
              data: {
                  id:  function() {
                      return $( "#client-id" ).val();
                    },
                }
            }
          },
          'phone': {
              minlength: 6,
              maxlength: 20
          },
          'email_address': {
            email: true,
          },
          'website': {
            url: true,
          }
        },
        messages: {
          finfo_account_name: {
              remote: "account name already in use!"
          }         
        },
        submitHandler: function(form) {
            form.submit();
        }
    });
</script>

@stop
