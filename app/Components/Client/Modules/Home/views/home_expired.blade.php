@extends($app_template['client.frontend'])
@section('content')
    <section class="content" id="home_expired">
        <div class="row main-title">
            <div class="col-md-12">
               Access Denied
            </div>
        </div>
        <div class="row main-content">
            <div class="col-md-12">
                <p>This page currently was disable by FINFO Administrator, please contact administrator.</p>
            </div>
        </div>
    </section>
@stop
@section('style')
<style>
    .main-content {
        padding-top: 50px;
        text-align: center;
    }
</style>
@stop
@section('seo')
    <title></title>
@stop
@section('script')
<script type="text/javascript">
</script>
@stop