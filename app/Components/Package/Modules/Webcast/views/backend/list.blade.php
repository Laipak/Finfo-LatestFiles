@extends($app_template['client.backend'])
@section('title')
Webcast
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
                <lable class="label-title">List of Webcast</lable>
            </div>
            <div class="col-sm-6"> 
                <div class="pull-right">
                    <a href="{{route('package.admin.webcast.form')}}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
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
        {!! Form::open(array('id' => 'form', 'method' => 'post')) !!}
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div id="box-page" class="box-body">
                        <table id="table-user" class="table table-bordered table-striped">
                            <thead>
                            <tr class="table-header">
                                <th class="hid"><input class="check-all" type="checkbox"></th>
                                <th>Quarter</th>
                                <th>Year</th>
                                <th>Url</th>
                                <th>Caption</th>
                                <th>Status</th>
                                <th>Publish date</th>
                                <th>Quick Actions</th>
                            </tr>
                            </thead>
                            <tbody> 
                                @foreach($data as $webcast)
                                <tr>
                                    <td width="15px" class="text-center"><input class="check-user" name="check[]" value="{{$webcast->id}}" type="checkbox"></td>
                                    <td>{{ucfirst($controller->getQuarterMonthById($webcast->quarter))}}</td>
                                    <td>{{$webcast->year}}</td>
                                    <td>{{$webcast->url}}</td>
                                    <td>{{$webcast->caption}}</td>
                                    <td>{!!$controller->getStatus($webcast->is_active)!!}</td>
                                    <td>{{ isset($webcast->publish_date) ? date('d F, Y', strtotime($webcast->publish_date)) : "-"}}</td>
                                    <td class="text-center"> <a href="{{route('package.admin.webcast.form', $webcast->id)}}"><i class="fa fa-edit fa-lg"></i></a> | <a _url="{{route('package.admin.webcast.delete', $webcast->id)}}" class="btn-delete-overide" style="background-color: transparent;cursor: pointer;"><i class="fa fa-trash-o fa-lg" style="color:red"></i></a></td>
                                </tr>
                                @endforeach
                      
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
     <input type="hidden" id="url" value="{{route('package.admin.webcast')}}">
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
{!! Html::script('js/bootstrap-checkbox.min.js') !!}

<script type="text/javascript">
    $('.active').removeClass('active');
    $('.web_cast').addClass('active');
    $('.web_cast_list').addClass('active');
    
    $.ajaxSetup({
        headers:{
            'X-CSRF-TOKEN':'{!! csrf_token() !!}'
        }
    });

    $( window ).resize(function() {
        var screen = $(window).width();
        if(screen < 770){
            $('#box-page').addClass('table-responsive');
        }else{
            $('#box-page').removeClass('table-responsive');
        }
    });

    $('#enable_filter').checkboxpicker().change(function() {
        var is_enable = 1;
        if ($('input#enable_filter').is(':checked')) {
            $('input#enable_filter').addClass('checked');
            is_enable = 1;
        } else {
            $('input#enable_filter').removeClass('checked');
            is_enable = 0;
        }
        data = { 'is_enable': is_enable};
        $.ajax({
            url: baseUrl + '/admin/press-releases/change_enable',
            type: "POST",
            data: data,
            success: function(data) {
                console.log(data);
            },
        });
    });


    $(document).ready(function(){
        var screen = $(window).width();
        if(screen < 770){
            $('#box-page').addClass('table-responsive');
        }else{
            $('#box-page').removeClass('table-responsive');
        }

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

    $("#table-user").on("click",".check-user:not(:checked)",function(){
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
