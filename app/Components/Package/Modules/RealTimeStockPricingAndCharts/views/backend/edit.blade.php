@extends($app_template['client.backend'])
@section('title')
Pricing and Charts
@stop
@section('content')
	<section class="content" id="pricing">
		@if(Session::has('global'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global') }}
            </div>
        @endif

        <?php 
            $border = array('0' => '0px', '1' => '1px', '2' => '2px'); 
            $view = array('1' => 'Weekly', '2' => 'Monthly', '12' => 'Yearly');
            $refresh = array('30000' => 'Every 30 Second','60000' => 'Every 1 Minutes','120000' => 'Every 2 Minutes','300000' => 'Every 5 Minutes', '600000' => 'Every 10 Minutes');
        ?>


        <div class="row head-search">
            <div class="col-sm-12">
                <lable class="label-title">Edit Pricing and Charts</lable>
            </div>
        </div>

        {!! Form::open(array('route' => 'package.admin.stock.save')) !!}

        <!-- chart colour setting !-->
        <h4 class="sub-title">Chart Colour Settings</h4>
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    <label>Button Colour</label>
                    <div class="input-group color-picker">
                        <input type="text" value="{{$setting->button_color}}" name="button_color" class="form-control">
                        <span class="input-group-addon btn-color"></span>
                    </div>
                    {!! $errors->first('button_color', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    <label>Text Colour</label>
                    <div class="input-group color-picker">
                        <input type="text" value="{{$setting->text_color}}" name="text_color" class="form-control">
                        <span class="input-group-addon btn-color"></span>
                    </div>
                    {!! $errors->first('text_color', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    <label>Highlight Colour</label>
                    <div class="input-group color-picker">
                        <input type="text" value="{{$setting->highlight_color}}" name="highlight_color" class="form-control">
                        <span class="input-group-addon btn-color"></span>
                    </div>
                    {!! $errors->first('highlight_color', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    <label>Background Colour</label>
                    <div class="input-group color-picker">
                        <input type="text" value="{{$setting->background_color}}" name="background_color" class="form-control">
                        <span class="input-group-addon btn-color"></span>
                    </div>
                    {!! $errors->first('background_color', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>




        <!-- chart table setting !-->
        <h4 class="sub-title">Chart Table Settings</h4>
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    <label>Border Thickness</label>
                    {!! Form::select('border_thickness', $border, $setting->border_thickness, array('class' => 'form-control')); !!}
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    <label>View Option</label>
                    {!! Form::select('view_option', $view, $setting->view_option, array('class' => 'form-control')); !!}
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    <label>Refresh Frequency</label>
                    {!! Form::select('refresh_frequency', $refresh, $setting->refresh_frequency, array('class' => 'form-control')); !!}
                </div>
            </div>
        </div>

        <!-- chart page setting !-->
        <h4 class="sub-title">Chart Page Settings</h4>
        <label>Choose Template</label>
        <div class="row">
            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    <div class="template-box">
                        <img src="/img/client/spline-chart.PNG" width="100%" height="100%">
                    </div>
                    <div class="label-box">
                        <p>Spline</p>
                        <input type="radio" name="chart_template" value="spline" class="minimal" {{( $setting->chart_template == 'spline' || $setting->chart_template == '')? 'checked' : ''}}/>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    <div class="template-box">
                        <img src="/img/client/colum-chart.PNG" width="100%" height="100%">    
                    </div>
                    <div class="label-box">
                        <p>Column</p>
                        <input type="radio" name="chart_template" value="column" class="minimal" {{($setting->chart_template == 'column')? 'checked' : ''}}/>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4">
                <div class="form-group">
                    <div class="template-box">
                        <img src="/img/client/splinearea-chart.PNG" width="100%" height="100%">
                    </div>
                    <div class="label-box">
                        <p>Spline Area</p>
                        <input type="radio" name="chart_template" value="splineArea" class="minimal" {{($setting->chart_template == 'splineArea')? 'checked' : ''}}/>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-top:30px;">
            {!! Form::submit('Save', ['class' => 'btn btn-primary btn-save']) !!}
            <a href="" class="btn btn-danger btn-overwrite-cancel">Cancel</a>
        </div>

        {!! Form::close() !!}

    </section>
@stop
@section('style')
	{!! Html::style('/css/client/pricing-charts.css') !!}
    {!! Html::style('/css/bootstrap-colorpicker.min.css') !!}
@stop

@section('script')
    {!! Html::script('/js/bootstrap-colorpicker.min.js') !!}

    <script>
        $('.active').removeClass('active');
        $('.real_time_st').addClass('active');
        $('.real_time_st_form').addClass('active');

        $(function(){
            $('.color-picker').colorpicker();
        });
    </script>

@stop