@extends($app_template['client.backend'])
@section('title')
Media Access
@stop
@section('content')
    <section class="content" id="press-release">
        @if(Session::has('successStatus'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('successStatus') }}
                    </div>
                </div>
            </div>
        @elseif(Session::has('successDeleted'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('successDeleted') }}
                    </div>
                </div>
            </div>
        @elseif(Session::has('successUpdated'))
            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('successUpdated') }}
                    </div>
                </div>
            </div>
        @endif
       
        <div class="row head-search">
            <div class="col-sm-6 title-mobile">
                <lable class="label-title">List of Media Access</lable>
            </div>
            <div class="col-sm-6"> 
                <div class="pull-right">
                    <a href="{{route('package.admin.media-access.form')}}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                    <a href="#" class="btn btn-success btn-flat btn-publish-all-files" style="padding: 4px 10px;">
                        <i class="fa fa-eye"></i> Publish
                    </a>
                    <a href="#" class="btn btn-warning btn-flat btn-unpublish-all-files" style="padding: 4px 10px;">
                        <i class="fa fa-eye-slash"></i> Unpublish
                    </a>
                    <a href="#" class="btn btn-danger btn-flat btn-delete-all-files" style="padding: 4px 10px;">
                        <i class="fa fa-trash"></i> Deleted
                    </a>
                    <!-- <a href="{{route('package.admin.media-access.form')}}" class="btn btn-success btn-flat btn-publish-all" style="padding: 4px 10px;">
                        <i class="fa fa-file"></i> Report
                    </a>-->
                </div>
            </div>            
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div id="box-media-files" class="box-body">
                        {!! Form::open(array('route' => 'package.admin.media-access.multi-delete-files',  'method' => 'post', 'files' => true, 'id' => 'frm_media_list_files' )) !!}
                        <table id="table-media-files" class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr class="table-header">
                                <th class="hid"><input class="check-all" type="checkbox"></th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Downloaded</th>
                                <th>Status</th>
                                <th>Expiry</th>
                                <th>Quick Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if (!empty($mediaAccessFiles)) 
                                    @foreach($mediaAccessFiles as $file) 
                                        <tr>
                                            <td width="15px" class="text-center"><input class="check-media-files" type="checkbox" name='check[]' value="{{$file->id}}"></td>
                                            <td>{{$file->title}}</td>
                                            <td>{{$file->category_name}}</td>
                                            <td>{{$controller->getCountMediaDownloaded($file->id)}} time(s)</td>
                                            <td>{{($file->status == 1) ? 'Unpublish': "Publish"}}</td>
                                            <td>{{date('d F, Y', strtotime($file->expire_date))}}</td>
                                            <td class="text-center"><a href="{{route('package.admin.media-access.edit', $file->id)}}"><i class="fa fa-edit fa-lg"></i></a> | <a href="{{route('package.admin.media-access.delete-file', $file->id)}}" title="delete" class='btn-action-file-delete'><i class="fa fa-trash-o fa-lg" style="color:red;"></i></a></td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('style')
    {!! Html::style('css/package/press-release.css') !!}
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
    $('.btn-publish-all-files').click(function(){
        if($("[type=checkbox]:checked").length > 0) {
            if(confirm('Are you sure want to publish files(s)?')){
                var $form = $('#frm_media_list_files');
                $form.attr('action', "{{route('package.admin.media-access.multi-publish-files')}}");
                $form.submit();
            } else {
                return false;
            }
        }else{
            alert('Please check at least one!');
            return false;
        }
    });
     $('.btn-unpublish-all-files').click(function(){
        if($("[type=checkbox]:checked").length > 0) {
            if(confirm('Are you sure want to unpublish files(s)?')){
                var $form = $('#frm_media_list_files');
                $form.attr('action', "{{route('package.admin.media-access.multi-unpublish-files')}}");
                $form.submit();
            } else {
                return false;
            }
        }else{
            alert('Please check at least one!');
            return false;
        }
    });
    $('.btn-delete-all-files').click(function(){
        if($("[type=checkbox]:checked").length > 0) {
            if(confirm('Are you sure want to delete files(s)?')){
                var $form = $('#frm_media_list_files');
                $form.submit();
            } else {
                return false;
            }
        }else{
            alert('Please check at least one!');
            return false;
        }
    });
    $('.btn-action-file-delete').click(function(){
        if(confirm('Are you sure you want to delete user?')){
            window.location = $(this).attr('href');
        } else {
            return false;
        }
    });
    $(document).ready(function(){
        var screen = $(window).width();
        if(screen < 770){
            $('#box-media-files').addClass('table-responsive');
        }else{
            $('#box-media-files').removeClass('table-responsive');
        }
        $("#table-media-files").dataTable({
            aoColumnDefs: [
                  {
                     bSortable: false,
                     aTargets: [ 0, 3 ]
                  }
                ]
        });
    });
    $("#table-media-files").on("click",".check-all:checked",function(){
        $(".check-media-files:checkbox:not(:checked)").click(); 
    });
    $('.check-all:not(:checked)').click(function(){ 
        $(".check-media-files:checkbox:checked").click(); 
    });
    $('.active').removeClass('active');
    $('.media_acc').addClass('active');
    $('.media-list').addClass('active');
</script>

@stop