@extends($app_template['client.frontend'])
@section('content')
@if ($active_theme == "default")
<section class="content">
<div class="row title" id="media-access">
    <div class="col-md-12">
        <div class="pull-left">Media Access</div>
        <div class="pull-right logout-text"><a href='{{route('package.media-access.logout')}}'>Logout</a></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12" id="table">
        <div class="content-data">
            @if(isset($getMediaFiles) && !empty($getMediaFiles))
                <table class="table table-media-file">
                    <thead>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Expire Date</th>
                        <th>Download</th>
                    </thead>
                    <tbody>
                        @foreach($getMediaFiles as $files)
                            <tr>
                                <td>{{ucfirst($files->title)}}</td>
                                <td>{{$files->description}}</td>
                                <td>{{date("d.m.y", strtotime($files->expire_date))}}</td>
                                <td><a href="#" data-auth-file="{{\Crypt::encrypt($files->id)}}" class="media-access-donwload" style="text-decoration: none;color: red;">Link</a></td>
                            </tr>
                        @endforeach
                    </tbody>        			
                </table>
            @endif
        </div>
    </div>
</div>
</section>
@else 
update me
@endif
@stop
@section('seo')
    <title>{{ucfirst(Session::get('company_name'))}} | Media Access</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('script')
<script type="text/javascript">
    $('.menu-active').removeClass('menu-active');
    $('#media-access').addClass('menu-active');
    $('.media-access-donwload').click(function(){
        var fileId = $(this).data('auth-file');
        $.ajax({
            url: "{{route('package.media-access.download')}}",
            type: "post",
            data: { file_id: fileId } ,
            success: function (response) {
                window.open(response, '_blank');
            },
            error: function(jqXHR, textStatus, errorThrown) {
               console.log(textStatus, errorThrown);
            }
        });
    });
</script>
@stop
@section('style')
<style>
    .table-media-file td {
        padding-left:8px !important;
        color:#000 !important;
        font-weight: 500 !important;
    }
    .table-media-file a:hover{
        text-decoration: underline !important;
    }
    #media-access .logout-text a:hover{
        text-decoration: underline;
    }
    #media-access .logout-text a{
        text-decoration: none;
    }
    #media-access .logout-text {
        font-size: 14px;
    }
</style>
    {!! Html::style('css/package/general-style.css') !!}
    {!! Html::style('css/package/media-access.css') !!}
@stop