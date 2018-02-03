@extends($app_template['backend'])
@section('content')
<section class="content" id="dashboard">
    @if (Session::has('msg_success'))
        <div class="alert alert-success">{{Session::get('msg_success')}}</div>
    @elseif (Session::has('msg_success'))
        <div class="alert alert-danger">{{Session::get('msg_error')}}</div>
    @endif
    <div class="row">
	    <div class="col-md-12">
	    	<div class="box">
	            <div class="box-header with-border">
	                <h4 class="box-title">Setting </h4>
	                <div class="pull-right gear-icon"><h4><i class="fa fa-gear"></i></h4></div>
	            </div><!-- /.box-header -->
	            <div class="box-body">
                    <div class="col-sm-6">
                        {!! Form::open(array('route'=>'finfo.admin.do.setting', 'id'=>'setting_form')) !!}
                        <div class="form-group" style="margin-top:30px;">
                                {!! Form::submit('Save', ['class' => 'btn btn-primary btn-flat']) !!}
                        </div>
                        <div class="form-group" style="text-align:left;">
                            {!! Form::label('sub_domain', 'Sub-domains to exclude') !!}
                            <span>(Separate by comma ",")</span>
                            {!! Form::textarea('sub_domain', $sub_domain_exclusive, ['class' => 'form-control', 'placeholder' => 'Sub-domains to exclude']) !!}
                        </div>
                        <div class="form-group" style="text-align:left;">
                            {!! Form::label('finfo_email', 'FINFO Email To Receive Notification When New Registration') !!}
                             <span>(Separate by comma ",")</span>
                            {!! Form::text('finfo_email', $admin_email_receive_noti, ['class' => 'form-control', 'placeholder' => 'example@domain.com,example1@domain.com']) !!}
                        </div>

                        <div class="form-group" style="text-align:left;">
                            {!! Form::label('from_email', 'Email of FINFO for sent to client') !!}
                            {!! Form::text('from_email', $admin_from_email, ['class' => 'form-control', 'placeholder' => 'example@domain.com']) !!}
                        </div>

                        <div class="form-group" style="text-align:left;">
                            <div class="row">
                                <div class="col-sm-6">
                                    {!! Form::label('broadcasts_per_year', 'Broadcasts/Year') !!}
                                {!! Form::number('broadcasts_per_year', $broadcasts_per_year, ['class' => 'form-control', 'placeholder' => 'Broadcasts/Year']) !!}
                                </div>
                                <div class="col-sm-6">
                                    {!! Form::label('recipients_per_year', 'Recipients/Broadcast') !!}
                                {!! Form::number('recipients_per_year', $recipients_per_year, ['class' => 'form-control', 'placeholder' => 'Recipients/Broadcast']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="text-align:left;">
                            {!! Form::label('lunch_market', 'Market Launches') !!}
                            <span>(Separate by comma ",")</span>
                            {!! Form::textarea('lunch_market', $lunch_market, ['class' => 'form-control']) !!}
                        </div>
                       
                        
                        
                    </div>
                    {!! Form::close()!!}
                    
                    
                    
                    
        
                    
                    
                    
                    
	            </div><!-- /.box-body -->
	        </div>
	    </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Currency</h4>
                    <div class="pull-right gear-icon"><h4><i class="fa fa-gear"></i></h4></div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    @if(Session::has('message'))
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Name</th>
                            <th>Symbol</th>
                            <th>Code</th>
                            <th>Exchange rate</th>
                            <th></th>
                        </tr>
                        @if(isset($currency))
                            @foreach($currency as $cur)
                            <tr>
                                <td>{{$cur->name}}</td>
                                <td>{{$cur->symbol}}</td>
                                <td>{{$cur->code}}</td>
                                <td>{{number_format ($cur->exchange_rate, 2)}}</td>
                                <td class='text-center'><i class="fa fa-edit fa-lg edit" data-id='{{$cur->id}}'></i> | <a href="{{route('finfo.admin.do.delete', $cur->id)}}" Onclick='return ConfirmDelete();'><i class="fa fa-trash-o fa-lg delete" ></i></a></td>
                            </tr>
                            @endforeach
                        @endif
                    </table>
                    <br><br>
                    <button type="button" class="btn btn-primary" id="btn-new">Add new</button>
                </div>
                    
                    <div class="modal fade" id="modal-currency">
                        <div class="modal-dialog modal-lg modal-cur">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="blue">Currency</h4>
                                </div>
                                {!! Form::open(array('route'=>'finfo.admin.do.currency', 'id' => 'frm-currency')) !!}
                                <div class="modal-body">
                                    <input  type="hidden" id="cur_id" name="cur_id">
                                    <div class="form-group" style="text-align:left;">
                                        {!! Form::label('name', 'Name') !!}
                                        {!! Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Name', 'id' => 'name']) !!}
                                    </div>
                                    <div class="form-group" style="text-align:left;">
                                        {!! Form::label('symbol', 'symbol') !!}
                                        {!! Form::text('symbol', '', ['class' => 'form-control', 'placeholder' => 'symbol', 'id' => 'symbol']) !!}
                                    </div>
                                    <div class="form-group" style="text-align:left;">
                                        {!! Form::label('code', 'code') !!}
                                        {!! Form::text('code', '', ['class' => 'form-control', 'placeholder' => 'code', 'id' => 'code']) !!}
                                    </div>
                                    <div class="form-group" style="text-align:left;">
                                        {!! Form::label('exchange_rate', 'Exchange rate') !!}
                                        {!! Form::text('exchange_rate', '', ['class' => 'form-control', 'placeholder' => 'Exchange rate', 'id' => 'exchange_rate']) !!}
                                    </div>
                                    <div class=" container-fluid text-center">
                                        <button type="submit" class="btn btn-primary continue" id="btn-save-gallery">Save</button>
                                    </div>
                                </div>
                                {!! Form::close()!!}
 
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Prices</h4>
                    <div class="pull-right gear-icon"><h4><i class="fa fa-gear"></i></h4></div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive">
                    @if(Session::has('package-message'))
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('package-message') }}
                        </div>
                    @endif
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Package</th>
                            <th>Prices (USD)</th>
                            <th></td>
                        </tr>
                        @if(isset($getPackageData))
                            @foreach($getPackageData as $package)
                            <tr>
                                <td>{{$package->title}}</td>
                                <td>$ {{$package->price}}</td>
                                <td class='text-center'><i class="fa fa-edit fa-lg edit-package-price" data-id='{{$package->id}}'></i> </td>
                            </tr>
                            @endforeach
                        @endif
                    </table>
                </div>
                <div class="modal fade" id="modal-pacakge-price">
                        <div class="modal-dialog modal-lg modal-pacakge-price">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close btn-modal-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="blue">Package prices</h4>
                                </div>
                                {!! Form::open(array('route'=>'finfo.admin.do.update-prices', 'id' => 'frm-package-price')) !!}
                                <div class="modal-body">
                                    <input type="hidden" id="pacakge-price-id" name="pacakge-price-id">
                                    <div class="form-group" style="text-align:left;">
                                        {!! Form::label('package_title', 'Title') !!}
                                        {!! Form::text('package_title', '', ['class' => 'form-control', 'placeholder' => 'Title', 'id' => 'package-title']) !!}
                                    </div>
                                    <div class="form-group" style="text-align:left;">
                                        {!! Form::label('package_price', 'price (USD)') !!}
                                        {!! Form::text('package_price', '', ['class' => 'form-control', 'placeholder' => 'Price', 'id' => 'package-price']) !!}
                                    </div>
                                    <div class=" container-fluid text-center">
                                        <button type="submit" class="btn btn-primary continue" id="btn-save">Save</button>
                                    </div>
                                </div>
                                {!! Form::close()!!}
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->
            </div>
            
            
            
            
            
                        
                      
   <h4 class="sub-title">Phone</h4>

    {!! Form::open(array('route' => 'finfo.admin.do.phone')) !!}
    
        <div class="row">
            <div class="form-group">
                <div class="col-md-9 col-sm-9">
                    
               
                    
                    <input type="text" class="form-control" name="phone"  required id="" value="{{$phone->phone}}">
                    {!! $errors->first('phone', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="col-md-3 col-sm-3">
                    <button type="submit" class="btn btn-primary btn-confirm">Save</button>
                </div>
            </div>
        </div>
    {!! Form::close() !!}

            
            
            
            
            
            
            
            
            
            
        </div>
    </div>
</section>
@stop
@section('style')
    <style type="text/css">
    .edit, .edit-package-price{
        color: green;
        cursor: pointer;
    }
    .delete{
        color: red;
        cursor: pointer;
    }
    .modal-cur, .modal-pacakge-price {
        width: 500px;
    }
    .error{
        color: red;
        font-weight: 500;
    }
    .btn-primary {
        border-radius: 0;
    }
    label {
        text-transform: capitalize;
    }
    </style>
@stop
@section('script')
{!! Html::script('js/jquery.validate.min.js') !!}
<script type="text/javascript">
    $(function(){
        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN':'{!! csrf_token() !!}'
            }
        });
        $('.edit-package-price').click(function(){
            var id = $(this).data('id');
            $.ajax({
                url: '/admin/get-package-prices',
                type: "POST",
                dataType: 'json',
                data: { id : id},
                success: function(data) {
                    $('#pacakge-price-id').val(id);
                    $('#package-title').val(data.title);
                    $('#package-price').val(data.price);
                    $('#modal-pacakge-price').modal('show');
                }
            });
        });
        $("#frm-package-price").validate({
            rules: {
                package_title: 'required',
                package_price: 'required'
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
        
        $('#btn-new').click(function(){
            $('#modal-currency').modal('show');
        });
        
        $("#frm-currency").validate({
            rules: {
                'name': 'required',
                'symbol': 'required',
                'exchange_rate': 'required',
                'code': 'required'
            },

            submitHandler: function(form) {
                form.submit();
            }
        });
        $('#modal-currency:first').on('hidden.bs.modal', function() {
            $('#cur_id').val('');
            $('#name').val('');
            $('#symbol').val('');
            $('#exchange_rate').val('');
            $('#code').val('');
        });

        function ConfirmDelete()
        {
          var x = confirm("Are you sure you want to delete?");
          if (x)
            return true;
          else
            return false;
        }

        $('.edit').click(function(){
            var dataId = $(this).data('id');
            var id = {
                        'id': dataId
                };

            $.ajax({
                url: '/admin/edit-currency',
                type: "POST",
                dataType: 'json',
                data: id,
                success: function(data) {
                    console.log(data);
                    $('#cur_id').val(dataId);
                    $('#name').val(data.name);
                    $('#symbol').val(data.symbol);
                    $('#code').val(data.code);
                    $('#exchange_rate').val(data.exchange_rate);
                    $('#modal-currency').modal('show');
                }
            });
        });
    });
</script>

@stop