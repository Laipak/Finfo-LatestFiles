@extends($app_template['client.backend'])
@section('title')
Media Access
@stop
@section('content')
<section class="content" id="media-access-category">
        <div class='row'>
            @if(Session::has('category_created'))
                <div class='col-md-12'>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('category_created') }}
                    </div>
                </div>
            @elseif(Session::has('categoryDeleted'))
                <div class='col-md-12'>
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        {{ Session::get('categoryDeleted') }}
                    </div>
                </div>
            @endif
        </div>
        <div class="row head-search">
            <div class="col-sm-6 title-mobile">
                <lable class="label-title">List category of media access</lable>
            </div>
            <div class="col-sm-6"> 
                <div class="pull-right">
                    <a href="{{route('package.admin.media-access.create-category')}}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                    <a href='#' class='btn btn-danger btn-flat btn-action-delete-all' style="padding: 4px 10px;">
                        <i class="fa fa-trash"></i> Deleted
                    </a>
                </div>
            </div>            
        </div>
        <div class="row">
           
            <div class="col-md-12">
                <div class="box">
                    <div id="box-category" class="box-body">
                        {!! Form::open(array('route'=> 'package.admin.media-access.multi-delete-category', 'id' => 'form_media_acess_delete_category', 'method' => 'post')) !!}
                        <table id="table-category" class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr class="table-header">
                                <th class="hid"><input class="check-category-all" type="checkbox"></th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Item</th>
                                <th class="text-center">Created Date</th>
                                <th class="text-center">Quick Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if (isset($mediaAccessCategoryInfo) && !empty($mediaAccessCategoryInfo))
                                    @foreach ($mediaAccessCategoryInfo as $category)
                                    <tr>
                                        <td width="15px" class="text-center"><input class="check-category" type="checkbox" name="check[]" value="{{$category->id}}"></td>
                                        <td class="text-center">{{$category->category_name}}</td>
                                        <td class="text-center">{{$controller->getMediaAccessCountFilesByCategory($category->id)}} items</td>
                                        <td class="text-center">{{date('d F, Y', strtotime($category->created_at))}}</td>
                                        <td class="text-center"><a href='{{route('package.admin.media-access.edit-category', $category->id )}}'><i class="fa fa-edit fa-lg"></i></a> | 
                                        <a href="{{route('package.admin.media-access.delete-category',$category->id )}}" class="btn-action-delete" title="delete"><i class="fa fa-trash-o fa-lg" style="color:red;"></i></a></td>
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
@stop
@section('script')

{!! Html::script('js/jquery.dataTables.min.js') !!}
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
    $(document).ready(function(){
        var screen = $(window).width();
        if(screen < 770){
            $('#box-category').addClass('table-responsive');
        }else{
            $('#box-category').removeClass('table-responsive');
        }
        $("#table-category").dataTable({
            aoColumnDefs: [
                {
                    bSortable: false,
                    aTargets: [ 0, 4 ]
                }
            ]
        });
    });
    $('.btn-action-delete').click(function(){
        if(confirm('Are you sure you want to delete media category?')){
            window.location = $(this).attr('href');
        } else {
            return false;
        }
    });
    $('.btn-action-delete-all').click(function(){
        if($("[type=checkbox]:checked").length > 0) {
            if(confirm('Are you sure want to deleted media category(s)?')){
                $('#form_media_acess_delete_category').submit();
            } else {
                return false;
            }
        }else{
            alert('Please check at least one!');
            return false;
        }
    }) ;
    $("#table-category").on("click",".check-category-all:checked",function(){
        $(".check-category:checkbox:not(:checked)").click(); 
    });

    $('.check-category-all:not(:checked)').click(function(){ 
        $(".check-category:checkbox:checked").click(); 
    });
    /*ACTIVE LEFT MENU*/
    $('.active').removeClass('active');
    $('.media_acc').addClass('active');
    $('.media-category').addClass('active');
</script>
@stop