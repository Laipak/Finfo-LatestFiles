@extends($app_template['client.backend'])
@section('title')
Investor Relations Event
@stop
@section('content')
    <section class="content" id="press-release">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Add New Investor Relations Event</lable>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">

                {!! Form::open(array('route' => 'package.admin.investor-relations-calendar.save', 'method' => 'POST', 'id' => 'myform')) !!}
                    <div class="form-group">
                            {!! Form::label('event_title', 'Event Title') !!}
                            {!! Form::text('event_title', '', ['class'=>'form-control', 'required' => 'true','placeholder'=>'Title']) !!}
                            {!! $errors->first('event_title', '<span class="help-block">:message</span>') !!}
                    </div>

                    <div class="form-group">
                            {!! Form::label('event_datetime', 'Publish date') !!}
                            <div id="date" class="input-group date">
                                {!! Form::text('event_datetime', Input::old('date'), ['class' => 'form-control','required' => 'true', 'placeholder' => 'Date']) !!}
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                            {!! $errors->first('event_datetime', '<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="form-group" style="margin-top:30px;">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}


                             <?php  $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/investor-relations-events";  ?> 
                        <a href="{{route('package.admin.investor-relations-calendar')}}" class="btn btn-danger btn-overwrite-cancel">Cancel</a>


                       <input type="button" class='btn btn-primary btn-overwrite-cancel' id="button" value="Preview">
                       
                       
                        <?php  $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]/investor-relations-events";  ?>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/package/press-release.css') !!}
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
@stop

@section('script')
<meta name="_token" content="{!! csrf_token() !!}"/>
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}







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

$(document).ready(function(){




    $('.active').removeClass('active');
    $('.invester_re_caln').addClass('active');
    $('.invester_re_caln_form').addClass('active');

    $('#date, #public-date').datetimepicker({
        format: 'DD MMMM, YYYY',
    });

    $('.upload-file').click(function(){
        $('#file').click();
    });

    //enable public or disable
    $("#press-release").on("click","#check-public:checked",function(){
        $('#public-date').prop('disabled', false);
    });

    $("#press-release").on("click","#check-public:not(:checked)",function(){
        $('#public-date').val('');
        $('#public-date').prop('disabled', true);
    });

   }); 
</script>

@stop
