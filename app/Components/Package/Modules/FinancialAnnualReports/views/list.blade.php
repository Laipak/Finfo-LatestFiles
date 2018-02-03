@extends($app_template['client.backend'])

@section('content')
    <section class="content" id="press-release">
        <div class="row head-search">
            <div class="col-sm-6">
                <h2 style="margin:0;">Financial Annual Reports</h2>
            </div>
            <div class="col-sm-6"> 
                <div class="pull-right">
                    <a href="{{route('package.admin.financial-annual-reports.create')}}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-plus"></i> New Financial Annual Reports
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            @if(Session::has('global'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('global') }}
                </div>
            @endif
            <div class="col-md-12">
                <div class="box">
                    <div id="box-user" class="box-body">
                        <table id="table-user" class="table table-bordered table-striped">
                            <thead>
                            <tr class="table-header">
                                <th class="hid"><input class="check-all" type="checkbox"></th>
                                <th>Title</th>
                                <th>Date</th>
                                <th>Quick Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                           
                                <tr>
                                    <td width="15px" class="text-center"><input class="check-user" type="checkbox"></td>
                                    <td>Report 1</td>
                                    <td>1 June 2015</td>
                                    <td class="text-center"> <a href=""><i class="fa fa-edit fa-lg"></i></a> | <a href="#"><i class="fa fa-download fa-lg" style="color:#5cb85c;"></i></a></td>
                                </tr>
                                <tr>
                                    <td width="15px" class="text-center"><input class="check-user" type="checkbox"></td>
                                    <td>Report 2</td>
                                    <td>15 October 2015</td>
                                    <td class="text-center"> <a href=""><i class="fa fa-edit fa-lg"></i></a> | <a href="#"><i class="fa fa-download fa-lg" style="color:#5cb85c;"></i></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/package/press-release.css') !!}
    {!! Html::style('css/dataTables.bootstrap.min.css') !!}
@stop

@section('script')

{!! Html::script('js/jquery.dataTables.min.js') !!}
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
    
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
                     aTargets: [ 0, 3 ]
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


</script>

@stop
