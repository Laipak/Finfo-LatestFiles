@extends($app_template['backend'])

@section('content')
    <section class="content" id="list-user">
        @if(Session::has('global'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global') }}
            </div>
        @endif
        @if(Session::has('global-danger'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global-danger') }}
            </div>
        @endif
        {!! Form::open(array('id' => 'form', 'method' => 'post')) !!}
        <div class="row head-search">
            <div class="col-sm-6">
                <h2 style="margin:0;">{{$controller->getStatusText($status)}}</h2>
            </div>
            <div class="col-sm-6">
                <div class="pull-right">

                    <button class="btn btn-danger btn-flat" id="delete-all" style="padding: 4px 10px;">
                        <i class="fa fa-trash"></i> Deleted
                    </button>

                </div>
            </div>
        </div>
        <div class="row">
           
            <div class="col-md-12">
                <div class="box">
                    <div id="box-user" class="box-body">
                        <table id="table-user" class="table table-bordered table-striped">
                            <thead>
                            <tr class="table-header">
                                <th class="hid"><input class="check-all" type="checkbox"></th>
                                <th>Invoice #</th>
                                <th>Client Name</th>
                                <th>Invoice Date</th>
                                <th>Start Date</th>
                                <th>Expired Date</th>
                                <th>Due Date</th>
                                @if($status == 3)
                                    <th>Cancelled Date</th>
                                @endif
                                <th>Total</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($invoices as $invoice)
                                <tr>
                                    <td width="15px" class="text-center"><input class="check-user" name="check[]" value="{{$invoice['id']}}" type="checkbox"></td>
                                    <td><a href="{{route('finfo.admin.billing.invoice.detail', $invoice['id'])}}">{{$invoice['invoice_number']}}</a></td>
                                    <td>{{ ucfirst($controller->getClientName($invoice['admin_id']))}}</td>
                                    <td>{{ date("d-M-Y", strtotime($invoice['invoice_date'])) }}</td>
                                    <td>{{ date("d-M-Y", strtotime($invoice['start_date'])) }}</td>
                                    <td>{{ date("d-M-Y", strtotime($invoice['expire_date'])) }}</td>
                                    <td>{{ date("d-M-Y", strtotime($invoice['due_date'])) }}</td>
                                    @if($status == 3)
                                        <td>{{ $invoice['cancelled_date']}}</td>
                                    @endif
                                    <td>$ {{ number_format(round($invoice['amount']), 2)}}</td>
                                    <td>{{ $controller->getPaymentMethod($invoice['payment_method_id'])}}</td>
                                    <td>{!! $controller->getStatus($invoice['status'])!!}</td>
                                    <td class="text-center"><a href="{{route('finfo.admin.billing.invoice.download', $invoice['id'])}}"><i class="fa fa-download fa-lg" style="color:green;"></i></a> | <a href="{{route('finfo.admin.billing.invoice.form', $invoice['id'])}}"><i class="fa fa-edit fa-lg"></i></a> | <a class="btn-delete-overide" _url="{{route('finfo.admin.billing.invoice.delete', [$invoice['id'], $status])}}" style="cursor:pointer;"><i class="fa fa-trash-o fa-lg" style="color:red"></i></a></td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="paginate pull-right">
                            <?php //echo $data['user']->render(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <input type="hidden" id="url" value="{{route('finfo.user.backend.list')}}">
            <input type="hidden" id="status" name="status" value="{{$status}}">
            </form>
    </section>
@stop
@section('style')
    {!! Html::style('css/finfo/list-user.css') !!}
    {!! Html::style('css/dataTables.bootstrap.min.css') !!}
    <style>
        @media(max-width: 1024px) {
            .btn.btn-flat {
                width: 100%;
            }
        }
    </style>
@stop

@section('script')

{!! Html::script('js/jquery.dataTables.min.js') !!}
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
    $('#m-invoice-{{$status}}').addClass('active');


    $( window ).resize(function() {
        var screen = $(window).width();
        if(screen < 770){
            $('#box-user').addClass('table-responsive');
        }else{
            $('#box-user').removeClass('table-responsive');
        }
    });

    $(document).ready(function(){
        $("#table-user").dataTable({
            aoColumnDefs: [
                  {
                     bSortable: false,
                     aTargets: [ 0, 8 ],
                  }
                ]
        });
    });

    $("#table-user").on("click",".check-all:checked",function(){
        $(".check-user:checkbox:not(:checked)").click(); 
    });

    $('.check-all:not(:checked)').click(function(){
        
        $(".check-user:checkbox:checked").click(); 
    });

    $("#table-user").on("click",".check-user:not(:checked)",function(){
        if($(".check-user:checkbox:checked").length <= 0){
            $('.check-all').prop('checked', false);
        }
        
    });

    $('#delete-all').click(function(){
        if($("[type=checkbox]:checked").length > 0)
        {
            if(confirm('Are you sure want to delete this?'))
            {
                var $form = $('#form');
                $form.attr('action',"/admin/billing/invoice/delete-multi");
                $form.submit();
            }else{
                return false;
            }
        }else{
            alert('Please check at least one!');
            return false;
        }
    });

    $('.btn-delete-overide').click(function(){
        if(confirm('Are you sure you want to delete this one?')){
            window.location = $(this).attr('_url');
        }
    });


</script>

@stop
