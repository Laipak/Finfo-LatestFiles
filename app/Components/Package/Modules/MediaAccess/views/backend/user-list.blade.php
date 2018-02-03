@extends($app_template['client.backend'])
@section('title')
Media Access
@stop
@section('content')
    <section class="content" id="media-access">
        <div class="row">
            <div class="col-md-12 ">
                @if(Session::has('mediaAccessUserDeleted'))
                    <div class="alert alert-danger">
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       {{ Session::get('mediaAccessUserDeleted') }}
                    </div>
                @elseif (Session::has('mediaAccessUserApproved'))
                    <div class="alert alert-success">
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       {{ Session::get('mediaAccessUserApproved') }}
                    </div>
                @elseif(Session::has('mediaAccessUserRejected'))
                    <div class="alert alert-danger">
                       <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                       {{ Session::get('mediaAccessUserRejected') }}
                    </div>
                @endif
            </div>
        </div> 
        <div class="row head-search">
            <div class="col-sm-6 title-mobile">
                <lable class="label-title">List of User Media Access</lable>
            </div>
            <div class="col-sm-6"> 
                <div class="pull-right">
                    <a href="#" class="btn btn-primary btn-flat" id='btn-action-approved' style="padding: 4px 10px;">
                        <i class="fa fa-plus"></i> Approved
                    </a>
                    <button class="btn btn-danger btn-flat btn-action-delete-all" style="padding: 4px 10px;">
                        <i class="fa fa-trash"></i> Deleted
                    </button>
                    
                    <a href="{{route('package.admin.media-access.export-report')}}">
                        <button class="btn btn-success btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-file-o"></i> Export
                        </button>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
           
            <div class="col-md-12">
                <div class="box">
                    <div id="box-media-user" class="box-body">
                        {!! Form::open(array('route'=> 'package.admin.media-access.multi-approval', 'id' => 'form_media_acess_users', 'method' => 'post')) !!}
                            <table id="table-media-user" class="table table-bordered table-striped table-responsive">
                                <thead>
                                <tr class="table-header">
                                    <th class="hid"><input class="check-media-all" type="checkbox"></th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Organization</th>
                                    <th>Status</th>
                                    <th>Quick Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($mediaAccessUserInfo)) 
                                        @foreach($mediaAccessUserInfo as $users) 
                                            <tr>
                                                <td width="15px" class="text-center"><input class="check-media-user" type="checkbox" value='{{$users->id}}' name='check[]'></td>
                                                <td>{{ $users->name }}</td>
                                                <td>{{ $users->email }}</td>
                                                <td>{{ $users->phone }}</td>
                                                <td>{{ $users->organization }}</td>
                                                <td>{{ ($users->status == 0) ? "Pending" : "Approved" }}</td>
                                                <td>
                                                    @if($users->status == 0)
                                                        <a href='{{route('package.admin.media-access.approval', $users->id)}}' class="btn-approved">Approved</a> |
                                                    @endif
                                                    <a href='{{route('package.admin.media-access.reject', $users->id)}}' class="btn-action-reject">Reject</a> |
                                                    <a href="{{route('package.admin.media-access.deleted', $users->id)}}" class='btn-action-delete' title="delete"><i class="fa fa-trash-o fa-lg" style="color:red;"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <input type="hidden" name="deleted" value="{{route('package.admin.media-access.mulit-deleted')}}" id="multi-deleted"/>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        
    </section>

@stop

@section('style')
    {!! Html::style('css/client/media-access.css') !!}
    {!! Html::style('css/dataTables.bootstrap.min.css') !!}
    <style type="text/css">
        hr {
            border-top: 1px solid #CBC7C7;
        }
        .btn-export,
        .btn-export {
            background-color:#6aa501;
        }

        .btn-delete {
                background-color: #A4A2A2;
        }

        .btn {
            margin-left: 10px !important;
            color: white;
            border-radius: 0px !important;
        }

        #press-release table tr td a {
            cursor: pointer;
        }
    </style>
@stop
@section('script')

{!! Html::script('js/jquery.dataTables.min.js') !!}
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
//    $( window ).resize(function() {
//        var screen = $(window).width();
//        if(screen < 770){
//            $('#box-media-user').addClass('table-responsive');
//        }else{
//            $('#box-meida-user').removeClass('table-responsive');
//        }
//    });
    $(document).ready(function(){
        var screen = $(window).width();
        if(screen < 770){
            $('#box-media-user').addClass('table-responsive');
        }else{
            $('#box-meida-user').removeClass('table-responsive');
        }
        $("#table-media-user").dataTable({
            aoColumnDefs: [
                  {
                     bSortable: false,
                     aTargets: [ 0, 3 ]
                  }
                ]
        });
    });
    $('.btn-action-delete').click(function(){
        if(confirm('Are you sure you want to delete user?')){
            window.location = $(this).attr('href');
        } else {
            return false;
        }
    });
    $('.btn-action-reject').click(function(){
        if(confirm('Are you sure you want to reject user?')){
            window.location = $(this).attr('href');
        } else {
            return false;
        }
    });
    
    $('.btn-action-delete-all').click(function(){
        if($("[type=checkbox]:checked").length > 0) {
            if(confirm('Are you sure want to deleted user(s)?')){
                var $form = $('#form_media_acess_users');
                $form.attr('action', $('#multi-deleted').val());
                $form.submit();
            } else {
                return false;
            }
        }else{
            alert('Please check at least one!');
            return false;
        }
    }) ;
    $('.btn-approved').click(function(){
        if(confirm('Are you sure you want to approved this user?')){
            window.location = $(this).attr('href');
        }else{
             return false;
        }
    });
    $('#btn-action-approved').click(function(){
        if($("[type=checkbox]:checked").length > 0)
        {
            if(confirm('Are you sure want to approved users?')){
                $('#form_media_acess_users').submit();
            } else {
                return false;
            }
        }else{
            alert('Please check at least one!');
            return false;
        }
    }) ;
    $("#table-media-user").on("click",".check-media-all:checked",function(){
        $(".check-media-user:checkbox:not(:checked)").click(); 
    });

    $('.check-media-all:not(:checked)').click(function(){
        $(".check-media-user:checkbox:checked").click(); 
    });
    $('.active').removeClass('active');
    $('.media_acc').addClass('active');
    $('.media-list-user').addClass('active');
</script>
@stop