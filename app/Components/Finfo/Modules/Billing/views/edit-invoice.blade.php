@extends($app_template['backend'])

@section('content')
    <section class="content" id="list-user">
        <div class="row head-search">
            <div class="col-sm-6">
                <h2 style="margin:0;">Edit Invoice</h2>
            </div>
            
        </div>
        <div class="row">
        <div class="col-sm-12">
                <div class="box">
                    <div class="box-body">
                    @if(Session::has('global'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ Session::get('global') }}
                        </div>
                    @endif
                    <div class="col-sm-6">
                        {!! Form::open(array('route' => ['finfo.admin.billing.invoice.update', $data->id], 'id' => 'frm_user')) !!}
                            <div class="form-group{{ $errors->has('invoice_number') ? ' has-error' : '' }}">
                                {!! Form::label('invoice_number', 'Invoice Number') !!}
                                {!! Form::text('invoice_number', $data->invoice_number, ['class' => 'form-control', 'placeholder' => 'Invoice Number', 'disabled']) !!}
                                {!! $errors->first('invoice_number', '<span class="help-block">:message</span>') !!}
                            </div>
                        
                            <div class="form-group{{ $errors->has('client_name') ? ' has-error' : '' }}">
                                {!! Form::label('client_name', 'Client Name') !!}
                                {!! Form::text('client_name', $controller->getClientName($data->admin_id), ['class' => 'form-control', 'placeholder' => 'Client Name', 'disabled']) !!}
                                {!! $errors->first('client_name', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group{{ $errors->has('due_date') ? ' has-error' : '' }}">
                                {!! Form::label('due_date', 'Due Date') !!}
                                {!! Form::text('due_date', '', ['class' => 'form-control', 'id' => 'due_date']) !!}
                                {!! $errors->first('due_date', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                                {!! Form::label('amount', 'Amount') !!}
                                {!! Form::text('amount', $data->amount, ['class' => 'form-control', 'placeholder' => 'Amount']) !!}
                                {!! $errors->first('amount', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                                {!! Form::label('payment_method', 'Payment Method') !!}
                                {!! Form::select('payment_method', [ '1' => 'ebay'] ,$data->payment_method_id, ['class' => 'form-control']) !!}
                                {!! $errors->first('payment_method', '<span class="help-block">:message</span>') !!}
                            </div>

                            <div class="form-group" style="margin-top:30px;">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                                <a href="{{route('finfo.admin.billing.invoice', 0)}}" class='btn btn-danger'>Cancel</a>
                            </div>
                            
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/bootstrap-datetimepicker.min.css') !!}
    {!! Html::style('css/finfo/list-user.css') !!}
    <style type="text/css">
    .error{
        color: red;
        font-weight: 500;
    }
    </style>
@stop

@section('script')
{!! Html::script('js/jquery.validate.min.js') !!}
{!! Html::script('js/moment.min.js') !!}
{!! Html::script('js/bootstrap-datetimepicker.min.js') !!}
<script type="text/javascript">
$(document).ready(function(){
    $.ajaxSetup({
                headers:{
                    'X-CSRF-TOKEN':'{!! csrf_token() !!}'
                }
            });
     $("#frm_user").validate({
        rules: {

        'email_address'    :{
                required:   true,
                email:      true,
                remote: {
                          url: '/admin/users/check-exit-email',
                          type: "post",
                      }
            },

        },
        messages: {
                email_address: {
                    remote: "Email already in use!"
                }
            },

        submitHandler: function(form) {
            form.submit();
        }
     });

     $('#due_date').datetimepicker({
        format: 'YYYY-MM-DD',
        @if($data->due_date != "0000-00-00 00:00:00")
            defaultDate: "{{ $data->due_date }}",
        @endif
        
    });
});
   
</script>

@stop
