@extends($app_template['client.backend'])
@section('title')
Newsletter & Broadcast
@stop
@section('content')
<section class="content" id="list-user">
    @if(Session::has('global'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{ Session::get('global') }}
        </div>
    @endif
    <div class="row head-search">
        <div class="col-sm-6">
            <lable class="label-title">Newsletter Detail</lable>
        </div>
        <div class="col-sm-6">
            <div class="pull-right">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">        

            <div class="box">
                <div id="box-user" class="box-body">

                    <div class="table-responsive">
                        <div class="col-lg-12">
                            <h4>Newsletter Information</h4>
                        </div>
                        <table class="table table-striped">
                            <tr>
                                <th class="col-md-4">Newsletter type</th>
                                <td>:</td>
                                <td>{{$controller->getNewsType($news->newsletter_type)}}</td>
                            </tr>
                            <tr>
                                <th>subject</th>
                                <td>:</td>
                                <td>{{$news->subject}}</td>
                            </tr>
                            <tr>
                                <th>Sender Email</th>
                                <td>:</td>
                                <td>{{$news->sender_email}}</td>
                            </tr>
                            <tr>
                                <th>Reply to name</th>
                                <td>:</td>
                                <td>{{$news->reply_to_name}}</td>
                            </tr>
                            <tr>
                                <th>Reply to email</th>
                                <td>:</td>
                                <td>{{$news->reply_to_email}}</td>
                            </tr>
                        </table>
                        <br><br>
                        <div class="col-lg-12">
                            <h4>Broadcast Information</h4>
                        </div>
                        
                        <table class="table table-striped">
                            @if($news->schedule_it == 1)
                                <tr>
                                    <th class="col-md-4">Status</th>
                                    <td class="col-md-1">:</td>
                                    <td>{{$controller->getNewletterStatus($news->schedule_it, $news->id)}}</td>
                                </tr>
                                <tr>
                                    <th>Broadcast date</th>
                                    <td>:</td>
                                    <td>{{$broadcast->broadcast_date}}</td>
                                </tr>
                                <tr>
                                    <th>Broadcast time</th>
                                    <td>:</td>
                                    <td>{{date("h:i A", strtotime($broadcast->broadcast_time))}}</td>
                                </tr>
                            @else
                                <tr>
                                    <th class="col-md-4 text-center">No Broadcast </th>
                                    
                                </tr>
                            @endif
                        </table>
                    </div>
                    <br><br>
                    <div class="col-md-12">
                        <div class="pull-left">
                            <a href="{{route('package.admin.newsletter-broadcast')}}" class="btn btn-danger btn-flat btn-back" style="padding: 4px 10px;min-width:70px;">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                        <div class="pull-right">
                            <a href="{{route('package.admin.newsletter-broadcast.send-test', $news->id)}}" title="Send test to all email seed list" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                                <i class="fa fa-envelope"></i> Send test
                            </a>

                            <a href="{{route('package.admin.newsletter-broadcast.form', $news->id)}}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                                <i class="fa fa-edit"></i> Edit
                            </a>

                            @if($news['newsletter_type'] == 1)
                                <a href="/{{$news['upload']}}" target="_brank" class="btn btn-info btn-flat" style="padding: 4px 10px;" title="preview your newsletter PDF">
                                <i class="fa fa-eye"></i> Preview
                            </a>
                            @else
                                <a href="{{route('package.admin.newsletter-broadcast.view.template', $news['id'])}}" title="preview your newsletter template" class="btn btn-info btn-flat" style="padding: 4px 10px;">
                                <i class="fa fa-eye"></i> Preview
                            </a>
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop

@section('script')
<script type="text/javascript">
    $('.active').removeClass('active');
    $('.news_brod').addClass('active');
    $('.news_brod_list').addClass('active');
</script>
@stop
