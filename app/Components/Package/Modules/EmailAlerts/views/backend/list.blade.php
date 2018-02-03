@extends($app_template['client.backend'])
@section('title')
Email Alert
@stop
@section('content')
    <section class="content" id="press-release">
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

        <div class="row head-search">
            <div class="col-sm-6 title-mobile">
                <lable class="label-title">List Email Alerts</lable>
            </div>
            <div class="col-sm-6"> 
                <div class="pull-right">

                    <!--<a href="{{route('package.admin.email-alerts.send-mail')}}" class="btn btn-info btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-envelope"></i> Test Send Mail
                    </a> !-->

                    <a href="{{route('package.admin.email-alerts.form')}}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                    <button class="btn btn-danger btn-flat btn-delete-all" style="padding: 4px 10px;">
                        <i class="fa fa-trash"></i> Deleted
                    </button>
                    <button class="btn btn-success btn-flat btn-publish-all" style="padding: 4px 10px;">
                        <i class="fa fa-eye"></i> Publish
                    </button>
                    <button class="btn btn-warning btn-flat btn-unpublish-all" style="padding: 4px 10px;">
                        <i class="fa fa-eye-slash"></i> Unpublish
                    </button> 
                    <a href="{{route('package.admin.email-alerts.export-cv')}}" class="btn btn-success btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-file"></i> Export
                    </a>
                    
                </div>
            </div>
        </div>
        {!! Form::open(array('id' => 'form', 'method' => 'post')) !!}
        <div class="row">
            
            <div class="col-md-12">
                <div class="box">
                    <div id="box-page" class="box-body">
                        <table id="table-email-alerts" class="table table-bordered table-striped">
                            <thead>
                            <tr class="table-header">
                                <th class="hid"><input class="check-all" type="checkbox"></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Category</th>
                                <th>Subscribed date</th>
                                <th>Status</th>
                                <th>Quick Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($emails as $email)
                                <tr>
                                    <td width="15px" class="text-center"><input class="check-user" name="check[]" value="{{$email['id']}}" type="checkbox"></td>
                                    <td>{{$email->name}}</td>
                                    <td>{{$email->email_address}}</td>
                                    <td><ol>{{$controller->getListCategory($email->id)}}</ol></td>
                                    <td>{{date("d F, Y", strtotime($email->created_at))}}</td>
                                    <td>{{$controller->getStatus($email->is_active)}}</td>
                                    <td class="text-center"> <a href="{{route('package.admin.email-alerts.form', $email->id)}}"><i class="fa fa-edit fa-lg"></i></a> | <a class="btn-delete-overide" style="background-color: transparent;" _url="{{route('package.admin.email-alerts.soft-delete', $email->id)}}"><i class="fa fa-trash-o fa-lg" style="color:red;cursor: pointer;"></i></a></td>
                                </tr>
                                @endforeach
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="url" value="{{route('package.admin.email-alerts')}}">
        </form>
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

    $('.active').removeClass('active');
    $('.email_alert').addClass('active');
    $('.email_alert_list').addClass('active');
    checkScreen();
    $( window ).resize(function() {
            checkScreen();
    });
    function checkScreen(){
        var screen = $(window).width();
        if(screen <= 1024){
                $('#box-page').addClass('table-responsive');
        }else{
                $('#box-page').removeClass('table-responsive');
        }
    }
    
    $(document).ready(function(){
        $("#table-email-alerts").dataTable({
            aoColumnDefs: [
                  {
                     bSortable: false,
                     aTargets: [ 0, 3 ]
                  }
                ]
        });
    });

    $("#table-email-alerts").on("click",".check-all:checked",function(){
        $(".check-user:checkbox:not(:checked)").click(); 
    });

    $('.check-all:not(:checked)').click(function(){
        
        $(".check-user:checkbox:checked").click(); 
    });
    $("#table-email-alerts").on("click",".check-user:not(:checked)",function(){
        if($(".check-user:checkbox:checked").length <= 0){
            $('.check-all').prop('checked', false);
        }
        
    });

    $('.btn-delete-overide').click(function(){
        if(confirm('Are you sure you want to delete this one?')){
            window.location = $(this).attr('_url');
        }
    });


</script>

@stop
