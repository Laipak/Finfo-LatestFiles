

@extends($app_template['frontend'])

@section('content')
<!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>{{ isset($pageData->title)? $pageData->title : "" }}</h2>
                    <hr class="star-primary">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                   {!! isset($pageData->content_description) ? $pageData->content_description : "" !!}
                </div>
            </div>
        </div>
        </section>
@stop
@section('seo')
    <meta name="description" content="{{ isset($pageData->meta_description)? $pageData->meta_description : "" }}">
    <meta name="keywords" content="{{ isset($pageData->meta_keyword)? $pageData->meta_keyword : "" }}">
    <title>{{ isset($pageData->title)? $pageData->title : "" }} | FINFO</title>
@stop
@section('style')
 <style>
     .main-footer{
        position: absolute;
        bottom: 0;
        width: 100%;
     }
 </style>
 @stop
 