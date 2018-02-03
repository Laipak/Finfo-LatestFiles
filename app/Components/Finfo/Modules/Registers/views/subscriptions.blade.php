@extends($app_template['frontend'])

@section('content')
<div class="panel-grid" id="pg-453-5" >
	<div class="panel-row-style-default panel-row-style nav-one-page" style="background-color: #ecf0f5; padding-top: 100px; padding-bottom: 100px; " overlay="background-color: rgba(255,255,255,0); " id="pricing">
		<div class="container">
			<div class="panel-grid-cell" id="pgc-453-5-0" >

				<div class="panel-builder widget-builder widget_origin_title panel-first-child" id="panel-453-4-0-0">
					<div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-dark">
						<h3  class="align-center">Packages</h3><div class="divider colored"></div>
					</div>
				</div>
					
				<div class="col-sm-offset-4 col-md-offset-7 col-sm-8 col-md-5">
                {!! Form::open(array('url'=>route('finfo.register.subscriptions'),'method' => 'GET')) !!}
                    <div class="form-group">
                        <lable class="control-labe" style="margin-top: 10px; font-size:15px;"><strong>Currency:</strong></lable>
                        {!! Form::select('currency', $currencyData , (isset($_GET['currency']))?$_GET['currency']: 2 , array('class'=>'form-control spacing','onchange'=>'this.form.submit()')) !!}
                    </div>
                {!! Form::close() !!}
		        </div>

				<div class="panel-builder widget-builder widget_origin_spacer" id="panel-453-5-0-1">
					<div class="origin-widget origin-widget-spacer origin-widget-spacer-simple-blank">
						<div style="margin-bottom:60px;"></div>
					</div>
				</div>				

				<div class="well well-sm panel-builder widget-builder widget_spot" id="panel-453-5-0-2">
					<div class="panel-grid" id="pg-905-0" >
						<div class="panel-row-style-default panel-row-style" >
							<div class="container">
								
								{!! Form::open() !!}
				<section id="plans">
			        <div class="container">
			            <div class="row">
					      	@foreach($package_module as $packages)
					        <!-- item -->
			                <div class="col-md-4 text-center">
			                    <div class="panel panel-info panel-pricing">
			                        <div class="panel-heading">			                            
			                            <h3>{{$packages['package']['name']}}</h3>
			                        </div>
			                        <div class="panel-body text-center">
                                                    <p><strong>{{(isset($exchange->symbol))?$exchange->symbol: '$' }} {{number_format(round((isset($exchange->exchange_rate))? $packages['package']['price'] * $exchange->exchange_rate : $packages['package']['price']), 2 , ".", "," )}}</strong>/ Month</p>
			                        </div>
			                        <ul class="list-group" style="text-align:left">
                                                    @foreach($packages['module'] as $module)
                                                        <li class="list-group-item"><i class="fa fa-check"></i> {{ $module->name }}</li>
                                                    @endforeach
			                        </ul>
			                        <div class="panel-footer">
                                                    {{--*/ $currencyType = 1 /*--}}
                                                    @if(isset($_GET['currency']))
                                                        {{--*/ $currencyType = $_GET['currency'] /*--}}
                                                    @endif
			                            <a class="btn btn-lg btn-block btn-info" href="{{ URL::to('register/'.$packages['package']['name'].'/'.$exchange->id)}}">SUBSCRIBE</a>
			                        </div>
			                    </div>
			                </div>
			                <!-- /item -->
                            @endforeach
			            </div>
			        </div>
			    </section>
			{!! Form::close() !!}

							</div>
						</div>
					</div>
				</div>

				<div class="panel-builder widget-builder widget_origin_title panel-last-child" id="panel-453-4-0-3">
					<div class="origin-widget origin-widget-title origin-widget-title-simple-simple align-center text-dark">
						<h3 class="align-center"></h3>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('seo')
    <title>Finfo | Registration</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop