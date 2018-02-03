@extends($app_template['client.backend'])
@section('title')
Announcement
@stop
@section('content')
    <section class="content" id="press-release">
        <div class='row'>
            <div class='col-md-12'>
                @if(Session::has('global'))
                    <div class="col-md-12 alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('global') }}
                    </div>
                @elseif(Session::has('deleted'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('deleted') }}
                    </div>
                @endif
            </div>    
        </div>
        <div class="row head-search">
            <div class="col-sm-6 title-mobile">
                <lable class="label-title">List of Announcements</lable>
            </div>
            <div class="col-sm-6"> 
                <div class="pull-right">
                    <a href="{{route('package.admin.announcements.form')}}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
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
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(array('id' => 'form', 'method' => 'post')) !!}
                <div class="box">
                    <div id="box-announcement" class="box-body">
                        <table id="table-announcement" class="table table-bordered table-striped">
                            <thead>
                            <tr class="table-header">
                                <th class="hid"><input class="check-all" type="checkbox"></th>
                                <th>Title</th>
                                <th style="display:none">Quarter</th>
                                <th style="display:none">Year</th>
                                <th>Publish date</th>
                                <th>Status</th>
                                <th>Quick Actions</th>
                            </tr>
                            </thead>
                            <tbody>                                
                                    @if($getAllAnnouncement)
                                        @foreach($getAllAnnouncement as $key => $value) 
                                            <tr>
                                                <td width="15px" class="text-center"><input class="check-announcement" type="checkbox" value='{{$value->id}}' name="check[]"></td>
                                                <td>{{$value->title}}</td>
                                                <td style="display:none">{{isset($value->quarterName)? $value->quarterName : "-"}}</td>
                                                <td style="display:none">{{isset($value->year)? $value->year : "-"}}</td>
                                                <td>{{date('d F, Y', strtotime($value->announce_at))}}</td>
                                                <td>{{($value->status == 0) ? "Publish" : "Unpublish"  }}</td>
                                                <td class="text-center">
                                                    <a href="{{route('package.admin.announcements.edit', $value->id)}}" title="edit">
                                                        <i class="fa fa-edit fa-lg"></i></a> | <a class="btn-delete" href="{{ route('package.admin.announcements.delete', $value->id)}}" title="delete" style="background-color: transparent;">
                                                        <i class="fa fa-trash-o fa-lg" style="color:red"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif    
                            </tbody>
                        </table>
                    </div>
                </div>
                <input type="hidden" id="url" value="{{route('package.admin.announcements')}}">
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/dataTables.bootstrap.min.css') !!}
     <style>
        #press-release th {
            color: #A4A2A2;    
        }
        #press-release tbody td{
            color: #A4A2A2
        } 
    </style>
@stop

@section('script')

{!! Html::script('js/jquery.dataTables.min.js') !!}
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
    $('.active').removeClass('active');
    $('.announce').addClass('active');
    $('.announce_list').addClass('active');


    $( window ).resize(function() {
        var screen = $(window).width();
        if(screen < 770){
            $('#box-announcement').addClass('table-responsive');
        }else{
            $('#box-announcement').removeClass('table-responsive');
        }
    });

    $(document).ready(function(){
        var screen = $(window).width();
        if(screen < 770){
            $('#box-announcement').addClass('table-responsive');
        }else{
            $('#box-announcement').removeClass('table-responsive');
        }
        $('.btn-delete').click(function(){
            if(confirm('Are you sure you want to delete this one?')){
                window.location = $(this).attr('href');
            }
        });
        $("#table-announcement").dataTable({
            aoColumnDefs: [
                  {
                     bSortable: false,
                    //aTargets: [ 0, 3 ]
                  }
                ]
        });
    });

    $("#table-announcement").on("click",".check-all:checked",function(){
        $(".check-announcement:checkbox:not(:checked)").click(); 
    });

    $('.check-all:not(:checked)').click(function(){
        $(".check-announcement:checkbox:checked").click(); 
    });
    
    
</script>

@stop
