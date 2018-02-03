@extends($app_template['client.backend'])
@section('title')
Company Info
@stop
@section('content')
    <section class="content" id="list-page">
        <div class="row head-search">
            <div class="col-sm-12">
                @if(Session::has('page_success_added'))
                    <div class=" box alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ trans(Session::get('page_success_added')) }}
                    </div>
                @elseif(Session::has('page_success_deleted'))
                    <div class="box alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ trans(Session::get('page_success_deleted')) }}
                    </div>
                @elseif(Session::has('page_maximum'))
                    <div class="box alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ trans(Session::get('page_maximum')) }}
                    </div>
                @endif
            </div>
            <div class="col-sm-6 col-md-6">
                <lable class="label-title">List of Pages</lable>
            </div>
            <div class="col-sm-6 col-md-6">
                <div class="pull-right">
                    <a href="{{route('client.webpage.backend.create')}}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                    <i class="fa fa-plus"></i> Add new
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
            <P>Maximum 3 sections can be added. The suggested sections are <strong>About Us </strong>, <strong> Board of Director </strong> and <strong>Contact Us</strong></P>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div id="box-page" class="box-body">
                        <table id="table-page" class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-header">
                                    <th class="hid"><input class="check-all" type="checkbox"></th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Order</th>
                                    <th>Date Created</th>
                                    <th>Created By</th>
                                    <th>Date Modify</th>
                                    <th>Modify By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($pageData) && !empty($pageData))
                                    @foreach($pageData as $page)
                                        <tr>
                                            <td width="15px" class="text-center"><input class="check-page" name="check[]" value="{{$page['id']}}" type="checkbox"></td>
                                            <td>{{$page['title']}}</td>
                                             <td>
                                                @if ($page['is_active'] == 0)
                                                    {{'Draft'}}
                                                @elseif($page['is_active'] == 1)
                                                    {{'Live'}}
                                                @endif
                                            </td>
                                            <td>{{$page['ordering']}}</td>
                                            <td>{{ date('d F, Y', strtotime($page['created_at']))}}</td>
                                            <td>{{ $page['created_by_user']}}</td>
                                            <td>{{ date('d F, Y', strtotime($page['updated_at']))}}</td>
                                            <td>{{ $page['updated_by_user']}}</td>
                                            <td class="text-center">
                                                <a href="{{route('client.webpage.backend.edit', $page['id'])}}"><i class="fa fa-edit fa-lg"></i></a> | <a class="btn-delete" _url="{{route('client.webpage.backend.soft.delete', $page['id'])}}"><i class="fa fa-trash-o fa-lg" style="color:red"></i></a>
                                            </td>
                                        </tr>
                                   @endforeach
                               @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
            <input type="hidden" id="url" value="{{route('client.webpage.backend.list')}}">
        {!! Form::close() !!}
    </section>
@stop

@section('style')
{!! Html::style('css/dataTables.bootstrap.min.css') !!}
{!! Html::style('css/client/webpage.css') !!}
    <style>
        #list-pages .head-search{
            margin-bottom: 30px;
            margin-top: 30px;
        }
        #list-pages table tr td{
                color: #A4A2A2;
        }   
        .btn-delete { background: transparent; cursor: pointer;}
        #list-page th {
            color: #A4A2A2;    
        }
        #list-page tbody td{
            color: #A4A2A2
        } 
    </style>
@stop

@section('script')
    {!! Html::script('js/jquery.dataTables.min.js') !!}
    {!! Html::script('js/dataTables.bootstrap.min.js') !!}
    <script>
        $( window ).resize(function() {
            var screen = $(window).width();
            if(screen < 770){
                    $('#box-page').addClass('table-responsive');
            }else{
                    $('#box-page').removeClass('table-responsive');
            }
	});
        $('.btn-delete').click(function(){
            if(confirm('Are you sure you want to delete this one?')){
                window.location = $(this).attr('_url');
            }
        });
        $(document).ready(function(){
            var screen = $(window).width();
            if(screen < 770){
                    $('#box-page').addClass('table-responsive');
            }else{
                    $('#box-page').removeClass('table-responsive');
            }
            $("#table-page").dataTable();
            $("#table-page").on("click",".check-all:checked",function(){
                $(".check-page:checkbox:not(:checked)").click(); 
            });
            $('.check-all:not(:checked)').click(function(){
                $(".check-page:checkbox:checked").click(); 
            });
            $("#table-page").on("click",".check-user:not(:checked)",function(){
                if($(".check-page:checkbox:checked").length <= 0){
                    $('.check-all').prop('checked', false);
                }	
            });
        });
    
        $('.active').removeClass('active');
        $('.company_info').addClass('active');
    
    </script>
@stop
