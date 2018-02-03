@extends($app_template['client.backend'])
@section('title')
Newsletter & Broadcast
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
                <lable class="label-title">List of Newsletter</lable>
            </div>

            <div class="col-sm-6"> 
                <div class="pull-right button-action">
                    <a href="{{route('package.admin.newsletter-broadcast.form')}}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                    <button class="btn btn-danger btn-flat btn-delete-all" style="padding: 4px 10px;">
                        <i class="fa fa-trash"></i> Deleted
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
                                    <th width="50%">Title</th>
                                   <!-- <th>Newsletter Type</th>
                                    <th>Sender Email</th>
                                    <th>Reply to Name</th>
                                    <th>Reply to Email</th> -->
                                    <th>Status</th>
                                    <th width="20%">Scheduled</th>
                                    <th>Quick Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            @foreach($data as $news)

                                <tr>
                                    <td width="15px" class="text-center"><input class="check-user" name="check[]" value="{{$news['id']}}" type="checkbox"></td>
                                    <td><a href="{{route('package.admin.newsletter-broadcast.detail', $news['id'])}}">{{$news['subject']}}</a></td>
                                       
                                  <!--  <td>{{$controller->getNewsType($news['newsletter_type'])}}</td>
                                    <td>{{$news['sender_email']}}</td>
                                    <td>{{$news['reply_to_name']}}</td>
                                    <td>{{$news['reply_to_email']}}</td>-->
                                    <td>{{$controller->getNewletterStatus($news['schedule_it'], $news['id'])}}</td>
        				
        				           
        				   <?php if(!empty($news['schedule_date'])){
								if(($news['schedule_date'] =='0000-00-00')){
									$today = '-';
								}else{
									$date=date_create($news['schedule_date'].$news['schedule_time']);
									$today =  date_format($date,"F j, Y, g:i a");
								}}else{
									 $today = '-';
								}
                            ?>
        				 
        				            <td>{{$today}}</td>
                            
                             
                                    <td class="text-center">
                                        <!--a href="{{route('package.admin.newsletter-broadcast.form', $news->id)}}" title="edit"><i class="fa fa-edit fa-lg"></i></a--> 
                                        @if($news['newsletter_type'] == 1)
                                            <a href="/{{$news['upload']}}" target="_brank"><i class="fa fa-eye fa-lg" style="color:#5cb85c;"></i></a> 
                                        @else
                                            <a href="{{route('package.admin.newsletter-broadcast.view.template', $news['id'])}}" title="download"><i class="fa fa-eye fa-lg" style="color:#5cb85c;"></i></a>
                                        @endif
                                        | <a class="btn-delete-overide" _url="{{route('package.admin.newsletter-broadcast.soft.delete', $news['id'])}}" style="background-color: transparent;cursor: pointer;"><i class="fa fa-trash-o fa-lg" style="color:red;"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <input type="hidden" id="url" value="{{route('package.admin.newsletter-broadcast')}}">
        </form>
    </section>
@stop
@section('style')
    {!! Html::style('css/package/press-release.css') !!}
    {!! Html::style('css/dataTables.bootstrap.min.css') !!}
    <style type="text/css">
    @media (max-width: 1024px) {
        .button-action{
            width: 100%;
        }
    }
    </style>
@stop

@section('script')

{!! Html::script('js/jquery.dataTables.min.js') !!}
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
    $('.active').removeClass('active');
    $('.news_brod').addClass('active');
    $('.news_brod_list').addClass('active');


    $(document).ready(function(){
        var screen = $(window).width();
        if(screen <= 1024){
            $('#box-page').addClass('table-responsive');
        }else{
            $('#box-page').removeClass('table-responsive');
        }
        $("#table-user").dataTable({
            aoColumnDefs: [
                  {
                     bSortable: false,
                     aTargets: [ 0, 7 ]
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
