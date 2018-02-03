@extends($app_template['client.frontend'])
@section('content')
    <section class="content" id="leadership">
         @if (isset($slugContents) && !empty($slugContents))
                <div class="row main-title">
                    <div class="col-md-12">                        
                        @if ($active_theme == "default")
                            {!! $slugContents->title!!}
                        @endif
                    </div>
                </div>
                <div class="content-data">{!! $slugContents->description !!}</div>
        @endif
    </section>
@stop
@section('style')
    {!! Html::style('css/client/leadership.css') !!}
@stop
@section('script')
    <script type="text/javascript">
        $('.active').removeClass('menu-active');
        $('#press-release').addClass('menu-active');
    </script>
@stop
  
@section('seo')
@if (isset($slugContents) && !empty($slugContents))
<title>{{ucfirst(Session::get('company_name'))}} | {{ $slugContents->title}}</title>
<meta name="description" content="{{$slugContents->description}}">
<meta name="keywords" content="{{ $slugContents->title}}">
@endif    
@stop