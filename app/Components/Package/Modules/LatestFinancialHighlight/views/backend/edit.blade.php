@extends($app_template['client.backend'])
@section('title')
Financials
@stop
@section('content')

<?php
  $currently_selected = date('Y'); 
  $earliest_year = 2008; 
  $latest_year = date('Y'); 


?>

<style>
	.form-control.frm-cntrl-lbl
	{
		border:none;
		box-shadow:none;
		padding-left: 0px;
		color:#333;
	}
	#finan-highlight .btn
	{
		text-transform: capitalize;
	}
	.frm-cntrl-input
	{
		background:#fff;
	}
	
	
	.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #ffff;
    border: 0px solid #ffff;
    border-radius: 0px;
    cursor: default;
    float: left;
    margin-right: 5px;
    margin-top: 5px;
    padding: 0 5px;
    }


    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: #ffff;
    cursor: pointer;
    display: inline-block;
    font-weight: bold;
    display: none;
    }
	

</style>
	<section class="content">
                @if(session()->has('success'))        
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session()->has('error'))        
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        {{ session('error') }}
                    </div>
                @endif
       {!! Form::open(array('route' => array('package.admin.latest-financial-highlights.form.update', $data[0]->latest_financial_highlights_id ), 'method' => 'post','id' => 'myform','files'=> true)) !!}

			<div class="row" id="finan-highlight">
				<div class="col-md-12">
		      	@if($data[0]->is_archive == 0) 
                 <lable class="label-title">Edit Financials</lable>  
                @endif
				<div class="clearfix" style="padding:5px;"></div>
				<div class="row">
					<div class="input_fields_wrap">
						<div class="col-md-12">
							<div class="row">								
								<div class="col-xs-12 col-md-9 col-lg-7">								
									<div class="col-xs-12 col-md-12">
										<div class="row">										
											
											<div class="col-xs-4 col-md-4">
												<div class="row">
													<input type="text" name="" id="input" value="Financial Result for year *" class="form-control frm-cntrl-lbl" readonly style="color:#999;">
												</div>
											</div>									  
											
											<div class="col-xs-8">
											<div class="row">
									<input id="tags" value="{{$data[0]->year}}" name="select_year" id="choose_usr_email" class="form-control">		   
											   
											    
							    	<!--<select multiple="true" name="select_year" id="choose_usr_email" class="form-control select2" required>
      
                                        @foreach($loadyear as $loadyear)
                                        <option value="{{$loadyear->year}}" 
                                        <?php if ($loadyear->year  == $data[0]->year ) echo 'selected = "selected"'; ?>
                                        >{{$loadyear->year}}</option>
                                        @endforeach
                                    </select> --> 
                                    

                                    <!--<input type="text" name="select_year" id="input" value="{{$data[0]->year}}" class="form-control frm-cntrl-input" placeholder="e.g. Year 2017">-->
																					</div>												
											</div>		
											
										</div>
									</div>	
								</div>
							</div>
						</div>	
					</div> 
				</div>
				<div class="clearfix" style="padding:5px;"></div>
	
				
				
				<div class="row">
					<div class="input_fields_wrap">
						<div class="col-md-12">
							<div class="row">								
								<div class="col-xs-12 col-md-9 col-lg-7">								
									<div class="col-xs-12 col-md-12">
										<div class="row">										
											
											<div class="col-xs-12 col-md-12">
												<div class="row">
													<input type="text" name="" id="input" value="Title *" class="form-control frm-cntrl-lbl" required readonly>
												</div>
											</div>									  
											
											<div class="row">
												<div class="col-xs-12">
									<input type="text" name="financial_highlight_title" id="input" value="{{$data[0]->f_title}}" class="form-control frm-cntrl-input" placeholder="Title">
										
												</div>												
											</div>		
											
										</div>
									</div>	
								</div>
							</div>
						</div>	
					</div> 
				</div>
				<div class="clearfix" style="padding:5px;"></div>
			
			@foreach($result as $results)
			@if(!empty($results->value))
				<div class="row">
					<div class="input_fields_wrap">
						<div class="col-md-12">
							<div class="row">								
								<div class="col-xs-12 col-md-9 col-lg-7 {{ $errors->has('values') ? ' has-error has-feedback' : '' }}">
									<!--<h5>Value</h5>-->								
									<div class="col-xs-12 col-md-12">
									  <div class="row">
									  
											<div class="col-xs-12 col-md-12 {{ $errors->has('titles') ? ' has-error has-feedback' : '' }}">
												<!--<h5>Insert Title</h5>-->
												<div class="row">
													<input type="text" name="titles[]" id="input" value="Financial Results" class="form-control frm-cntrl-lbl" readonly>
													{!! $errors->first('titles', '<span class="help-block" style="color:red">:message</span>') !!}
												</div>
											</div>
										
											<input type="file" class="hidden" accept="application/pdf" id="file" name="myfile">
											<div class="row">
												<div class="col-xs-9 upload-file">
													{!! Form::text('values', $results->value, ['class' => 'file-upload-input form-control','required' => 'true', 'placeholder' => 'Support only pdf', 'id' => 'file_upload', 'readonly' => '','style'=>'width: 100%;']) !!}
												</div>
												<div class="col-xs-3 div-sel-file">
													<button type="button" class="btn btn-upload upload-file">Select File</button>
												</div>
											</div>
											{!! $errors->first('file_upload', '<span class="help-block">:message</span>') !!}
										</div>
									 </div>
									{!! $errors->first('values', '<span class="help-block" style="color:red"	>:message</span>') !!}
								</div>	
							</div>
						</div>	
					</div> 
				</div> 				
				<div class="clearfix" style="padding:5px;"></div>
	        @endif
	        @endforeach	
           
            @foreach($presentation as $presentations)
            @if(!empty($presentations->value))
				<div class="row">
					<div class="input_fields_wrap">
						<div class="col-md-12">
							<div class="row">							
								<div class="col-xs-12 col-md-9 col-lg-7">	
									<div class="col-xs-12 col-md-12">
									  <div class="row">									
											<div class="col-xs-12 col-md-12 {{ $errors->has('titles') ? ' has-error has-feedback' : '' }}">
												<div class="row">
													<input type="text" name="titles[]" id="input" value="Presentation" class="form-control frm-cntrl-lbl" readonly>
												{!! $errors->first('titles', '<span class="help-block" style="color:red">:message</span>') !!}
												</div>
											</div>
											
											<input type="file" class="hidden" accept="application/pdf" id="file1" name="myfile1">
										
											<div class="row">
												<div class="col-xs-9 upload-file1">
													{!! Form::text('values1', $presentations->value, ['class' => 'file-upload-input form-control','required' => 'true', 'placeholder' => 'Support only pdf', 'id' => 'file_upload1', 'readonly' => '','style'=>'width: 100%;']) !!}
												</div>
												<div class="col-xs-3 div-sel-file">
													<button type="button" class="btn btn-upload upload-file1" >Select File</button>
												</div>
											</div>
											{!! $errors->first('file_upload', '<span class="help-block">:message</span>') !!}	
										</div>
									</div>
									
								</div>	
								
							</div>
						</div>	
					</div> 
				</div> 
				<div class="clearfix" style="padding:5px;"></div>
			@endif
			@endforeach
		
		
			@foreach($press as $presss)	
			@if(!empty($presss->value))
    			<div class="row">
					<div class="input_fields_wrap">
						<div class="col-md-12">
							<div class="row">
								<div class="col-xs-12 col-md-9 col-lg-7">
									<div class="col-xs-12 col-md-12">
										<div class="row">										
											<div class="col-xs-12 col-md-12 {{ $errors->has('titles') ? ' has-error has-feedback' : '' }}">
												<div class="row">
													<input type="text" name="titles[]" id="input" value="Press Release" class="form-control frm-cntrl-lbl" readonly>
												{!! $errors->first('titles', '<span class="help-block" style="color:red">:message</span>') !!}
												</div>												
											</div>									  
											<input type="file" class="hidden" accept="application/pdf" id="file2" name="myfile2">
											<div class="row">
												<div class="col-xs-9 upload-file2">
													{!! Form::text('values2', $presss->value, ['class' => 'file-upload-input form-control','required' => 'true', 'placeholder' => 'Support only pdf', 'id' => 'file_upload2', 'readonly' => '','style'=>'width: 100%;']) !!}
												</div>
												<div class="col-xs-3 div-sel-file">
													<button type="button" class="btn btn-upload upload-file2">Select File</button>
												</div>
											</div>
											{!! $errors->first('file_upload', '<span class="help-block">:message</span>') !!}
										</div>	
									 </div>								
								</div>									 
							</div>
						</div>	
					</div>
				</div> 
				<div class="clearfix" style="padding:5px;"></div>
            @endif
            @endforeach
            
           
           	@foreach($webcast as $webcasts)	
            @if(!empty($webcasts->value))
				<div class="row">
					<div class="input_fields_wrap">
						<div class="col-md-12">
							<div class="row">								
								<div class="col-xs-12 col-md-9 col-lg-7">								
									<div class=" col-xs-12 col-md-12">
										<div class="row">										
											
											<div class="col-xs-12 col-md-12">
												<div class="row">
													<input type="text" name="titles[]" id="input" value="Webcast" class="form-control frm-cntrl-lbl" readonly>
												</div>
											</div>									  
											
											<div class="row">
												<div class="col-xs-12 upload-file3">
													<input type="text" name="webcas" id="input" value="{{$webcasts->value}}" class="form-control frm-cntrl-input" placeholder="https://www.url.com">
													
												
												</div>												
											</div>		
											
										</div>
									</div>	
								</div>
							</div>
						</div>	
					</div> 
				</div>
            @endif
            @endforeach
           	
				<hr style="border-color:transparent;margin:22.5px 0;">
				
				<div class="row">



				   <?php  $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/financial-result";  ?> 
                                    <div class="col-md-12 col-sm-12">
                                            <button name="save" class="btn btn-primary btn-save">Save</button>
                                            <a href="{{route('package.admin.latest-financial-highlights')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>




                                            <input type="button" name="savepreview" class='btn btn-primary btn-overwrite-cancel' id="button" value="Preview">
                                    </div>


				</div>
				
				  <?php  $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/financial-result";  ?>

		</div><!--  end main row -->	
		</form>
	</section>
	
@stop

@section('style')
	{!! Html::style('css/client/finan-highlight.css') !!}
	{!! Html::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') !!}
	{!! Html::style('css/package/financial-annual-report.css') !!}
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
   	{!! Html::style('//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css') !!}
   	{!! Html::style('/resources/demos/style.css') !!}
    
@stop
@section('script')

{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
{!! Html::script('js/jquery.validate.min.js') !!}
{!! Html::style('https://code.jquery.com/jquery-1.12.4.js') !!}
{!! Html::style('https://code.jquery.com/ui/1.12.1/jquery-ui.js') !!}


<script>

$( function() {
    var availableTags =[<?php echo ($year_list); ?>];

    $( "#tags" ).autocomplete({
      source: availableTags
    });
  } );
  </script>

<meta name="_token" content="{!! csrf_token() !!}"/>

<script type="text/javascript">

    $(document).ready(function(){
    
    $('#choose_usr_email').select2({
        tags: true,
        maximumSelectionLength: 1
    });
    
    });


    $('.upload-file').click(function(){
        $('#file').click();
       
    });

    $('.upload-file1').click(function(){
        $('#file1').click();
    });

    $('.upload-file2').click(function(){
        $('#file2').click();
    });

    $('.upload-file3').click(function(){
        $('#file3').click();
    });
    
    
    $('#file').change(function(e) {
        $('#file_upload').val('');
        

        var file = this.files[0];
        var filetype = file.type;
        var filesize = file.size;
        var filename = file.name;



        var match = ["application/pdf"];
      /*  if (!((filetype == match[0]))) {
            alert('Please select pdf file.');
            if ('function' == typeof pCallback) {
                pCallback(false);
            }
            return;
        } */

        $('#file_upload').val(filename);
      
        
    });


  $('#file1').change(function(e) {
         $('#file_upload1').val('');

        var file = this.files[0];
        var filetype = file.type;
        var filesize = file.size;
        var filename = file.name;



    /*    var match = ["application/pdf"];
        if (!((filetype == match[0]))) {
            alert('Please select pdf file.');
            if ('function' == typeof pCallback) {
                pCallback(false);
            }
            return;
        } */

        $('#file_upload1').val(filename);
        
    });
    
    $('#file2').change(function(e) {
         $('#file_upload2').val('');

        var file = this.files[0];
        var filetype = file.type;
        var filesize = file.size;
        var filename = file.name;


   /*     var match = ["application/pdf"];
        if (!((filetype == match[0]))) {
            alert('Please select pdf file.');
            if ('function' == typeof pCallback) {
                pCallback(false);
            }
            return;
        } */

        $('#file_upload2').val(filename);
        
    });
    
    $('#file3').change(function(e) {
         $('#file_upload3').val('');

        var file = this.files[0];
        var filetype = file.type;
        var filesize = file.size;
        var filename = file.name;



   /*     var match = ["application/pdf"];
        if (!((filetype == match[0]))) {
            alert('Please select pdf file.');
            if ('function' == typeof pCallback) {
                pCallback(false);
            }
            return;
        } */

        $('#file_upload3').val(filename);
        
    });    
 
</script>

<script type="text/javascript">
$.ajaxSetup({
   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
});
</script>
<script>
$(document).ready(function(){
function toObject(arr) {
  var rv = {};
  for (var i = 0; i < arr.length; ++i)
    rv[i] = arr[i];
  return rv;
}
/*  $("#button").click(function(e){
        e.preventDefault();
  		var form = jQuery('#myform');
        var dataString = form.serializeArray();
        dataString.push({'name': 'preview','value': 'value'});
        console.log(dataString);
        var formAction = form.attr('action');*/
        
        
        
        
        
          $("#button").on('click', function(e) {
        e.preventDefault();
        
      var formvalid = $("#myform").valid();
  		var form = jQuery('#myform');
        var dataString = form.serializeArray();
        dataString.push({'name': 'preview','value': 'value'});
        var formAction = form.attr('action');


if(formvalid)
        
        
        
        
        

        $.ajax({
                type: "POST",
                url : formAction,
                data : dataString,
               
                success : function(data){
                  
                window.open('<?php echo  $actual_link ?>'); 
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    alert(msg);
                },
            });
    
      });
});
</script>

	<script type="text/javascript">
	$('.active').removeClass('active');
    $('.last_fin_hig').addClass('active');
    $('.last_fin_hig_form').addClass('active');
    
		(function($) {
 
	    	$( "#publish_date" ).datepicker({
	    			dateFormat: "dd M yy"
	    		}
			);
	 
		// Add more field
	    var max_fields      = 50; //maximum input boxes allowed
	    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
	    var add_button      = $(".add_field_button"); //Add button ID

	    var x = 1; //initlal text box count
	    $(add_button).click(function(e){ //on add input button click
	        e.preventDefault();
	        if(x < max_fields){ //max input box allowed
	            x++; //text box increment
	            $(wrapper).append('<div class="col-md-12"><div class="row"><div class="col-xs-5 col-md-3"><h5 class="hide-md">Insert Title</h5><input type="text" name="titles[]" id="input" class="form-control"></div><div class="col-xs-11 col-xs-5 col-md-3"><h5 class="hide-md">Value</h5><input type="number" name="values[]" class="form-control" step="any"></div><a href="#" class="remove_field" title="remove"><i class="fa fa-trash-o fa-lg" style="color:red"></i></a></div></div>'); //add input box
	        }
	    });

	    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
	        e.preventDefault(); $(this).parent('div').parent('div').remove(); x--;
	    });


		  // Browser supports HTML5 multiple file?
		  var multipleSupport = typeof $('<input/>')[0].multiple !== 'undefined',
		      isIE = /msie/i.test( navigator.userAgent );

		  $.fn.customFile = function() {

		    return this.each(function() {

		      var $file = $(this).addClass('custom-file-upload-hidden'), // the original file input
		          $wrap = $('<div class="form-group">'),
		          $input = $('<div class="col-md-3"><input type="text" class="file-upload-input form-control col-md-3" /></div>'),
		          // Button that will be used in non-IE browsers
		          $button = $('<div class="col-md-3"><button type="button" class="btn">Select File</button></div>'),
		          // Hack for IE
		          $label = $('<label class="btn" for="'+ $file[0].id +'">Select  File</label>');

		      // Hide by shifting to the left so we
		      // can still trigger events
		      $file.css({
		        position: 'absolute',
		        left: '-9999px'
		      });

		      $wrap.insertAfter( $file )
		        .append( $file, $input, ( isIE ? $label : $button ) );

		      // Prevent focus
		      $file.attr('tabIndex', -1);
		      $button.attr('tabIndex', -1);

		      $button.click(function () {
		        $file.focus().click(); // Open dialog
		      });

		      $file.change(function() {

		        var files = [], fileArr, filename;

		        // If multiple is supported then extract
		        // all filenames from the file array
		        if ( multipleSupport ) {
		          fileArr = $file[0].files;
		          for ( var i = 0, len = fileArr.length; i < len; i++ ) {
		            files.push( fileArr[i].name );
		          }
		          filename = files.join(', ');

		        // If not supported then just take the value
		        // and remove the path to just show the filename
		        } else {
		          filename = $file.val().split('\\').pop();
		        }

		        $input.val( filename ) // Set the value
		          .attr('title', filename) // Show filename in title tootlip
		          .focus(); // Regain focus

		      });

		      $input.on({
		        blur: function() { $file.trigger('blur'); },
		        keydown: function( e ) {
		          if ( e.which === 13 ) { // Enter
		            if ( !isIE ) { $file.trigger('click'); }
		          } else if ( e.which === 8 || e.which === 46 ) { // Backspace & Del
		            // On some browsers the value is read-only
		            // with this trick we remove the old input and add
		            // a clean clone with all the original events attached
		            $file.replaceWith( $file = $file.clone( true ) );
		            $file.trigger('change');
		            $input.val('');
		          } else if ( e.which === 9 ){ // TAB
		            return;
		          } else { // All other keys
		            return false;
		          }
		        }
		      });

		    });

		  };

		  // Old browser fallback
		  if ( !multipleSupport ) {
		    $( document ).on('change', 'input.customfile', function() {

		      var $this = $(this),
		          // Create a unique ID so we
		          // can attach the label to the input
		          uniqId = 'customfile_'+ (new Date()).getTime(),
		          $wrap = $this.parent(),

		          // Filter empty input
		          $inputs = $wrap.siblings().find('.file-upload-input')
		            .filter(function(){ return !this.value }),

		          $file = $('<input type="file" id="'+ uniqId +'" name="'+ $this.attr('name') +'"/>');

		      // 1ms timeout so it runs after all other events
		      // that modify the value have triggered
		      setTimeout(function() {
		        // Add a new input
		        if ( $this.val() ) {
		          // Check for empty fields to prevent
		          // creating new inputs when changing files
		          if ( !$inputs.length ) {
		            $wrap.after( $file );
		            $file.customFile();
		          }
		        // Remove and reorganize inputs
		        } else {
		          $inputs.parent().remove();
		          // Move the input so it's always last on the list
		          $wrap.appendTo( $wrap.parent() );
		          $wrap.find('input').focus();
		        }
		      }, 1);

		    });
		  }

}(jQuery));

//$('input[type=file]').customFile();








	</script>


@stop