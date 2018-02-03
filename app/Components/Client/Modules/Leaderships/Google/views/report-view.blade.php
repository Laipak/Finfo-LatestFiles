@extends($app_template['client.backend'])
@section('title')
Google Report Views
@stop
@section('content')
    <section class="content" id="google-report-views">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <label class="form-group ">Client ID</label>
                {!! Form::open(array('route' => 'google.clientid.add', 'method'=> 'post'))!!}
                    <div class="form-group col-md-10 col-sm-8">
                        {!! Form::text('client-id', isset($data) ? $data : "", ['class' => 'form-control', 'placeholder' => 'Google Client ID for Web application']) !!}
                    </div>
                    <div class="col-md-2 col-sm-4">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-6">
                <div id="embed-api-auth-container"></div>
                <div id="chart-container"></div>
                <div id="view-selector-container"></div>
            </div>
        </div>
    </section>
@stop
@section('style')
    <style>
        .btn-save{
            margin: 0px !important;
        }
    </style>
@stop