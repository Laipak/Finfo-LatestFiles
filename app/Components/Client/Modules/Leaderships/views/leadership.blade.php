@extends($app_template['client.frontend'])
@section('content')
    <section class="content" id="leadership">
        <div class="row main-title">
            <div class="col-md-12">
               Company Profile
            </div>
        </div>
        @if (isset($contents) && !empty($contents))    
            @foreach($contents as $content)
                <div class="row profile">
                    <div class="col-sm-12">
                        <div class="row col-sm-3 div-img">
                            <img src="/img/client/webpage/feature-images/{{ $content['feature_image'] }}" class="img-profile" >
                        </div>
                        <div class="col-sm-9 profile-detail">               
                            <h3 class="profile-name">{{ $content['title'] }}</h3>
                            {!! $content['content_description'] !!}
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </section>
@stop
@section('style')
	{!! Html::style('css/client/leadership.css') !!}
@stop

@section('script')

<script type="text/javascript">
    $('.menu-active').removeClass('menu-active');
    $('#leadership').addClass('menu-active');
</script>

@stop