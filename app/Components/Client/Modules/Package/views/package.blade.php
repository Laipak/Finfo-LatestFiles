@extends($app_template['client.backend'])
@section('title')
Package Information
@stop
@section('content')
	<section class="content" id="package">
        @if(Session::has('global'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global') }}
            </div>
        @endif
        @if($is_expire)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Your account have been expired. Please renew your account.
            </div>
        @endif
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Package Information</lable>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-11 col-md-6 dev-select" style="margin-top: 30px;">
                <div class="form-group">
                <lable class="col-sm-2 control-labe" style="margin-top: 6px; font-size:15px;"><strong>Currency:</strong></lable>
                <div class="col-sm-10">
                    {!! Form::open(array('url'=>route('client.admin.package'),'method' => 'GET')) !!}
                        {!! Form::select('currency', $currency , (isset($_GET['currency']))?$_GET['currency']:$currency_id , array('class'=>'form-control','onchange'=>'this.form.submit()')) !!}
                    {!! Form::close() !!}
                </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            {!! Form::open() !!}
                <section id="plans">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 pull-right unsubscribed">
                            <a class="btn btn-lg btn-block btn-danger no-radius btn-unsubscribed" href="{{URL::route('client.admin.cancel.package')}}">UNSUBSCRIBE</a>
                        </div>
                    </div>
                    <div class="row">
                        
                        @foreach($package_module as $packages)
                        <!-- item -->
                        <div class="col-md-4 text-center">
                            <div class="panel panel-info panel-pricing">
                                <div class="panel-heading">                                     
                                    <h3>{{$packages['package']['name']}}</h3>
                                </div>
                                <div class="panel-body text-center">
                                    <p><strong>

                                    @if(@$exchange->id == 11)
                                        {{$exchange->symbol}} 
                                        {{number_format(round($packages['package']['price_aud']), 2 , ".", "," )}}
                                    @else
                                        {{(isset($exchange->symbol))?$exchange->symbol: '$' }}
                                        {{number_format(round((isset($exchange->exchange_rate))? $packages['package']['price'] * $exchange->exchange_rate : $packages['package']['price']), 2 , ".", "," )}}
                                    @endif
                                            </strong>/ Month</p>
                                </div>
                                <ul class="list-group" style="text-align:left">
                                                @foreach($packages['module'] as $module)
                                                    <li class="list-group-item"><i class="fa fa-check"></i> {{ $module->name }}</li>

                                                    @if($module->ordering == 4)
                                                        <li class="list-group-item"><i class="fa fa-check"></i> Past Financial Results</li>
                                                    @endif
                                                    <!-- 
                                                    @if ($module->ordering == 6)
                                                        <li class="list-group-item"><i class="fa fa-check"></i> Page Manager</li>
                                                    @endif 
                                                    -->
                                                    @if ($packages['package']['name'] == 'Enterprise' && $module->ordering == 6)
                                                        <li class="list-group-item"><i class="fa fa-check"></i> Subscription Tool</li>
                                                    @endif
                                                @endforeach
                                </ul>
                                <div class="panel-footer">
                                                {{--*/ $currencyType = 1 /*--}}
                                                @if(isset($_GET['currency']))
                                                    {{--*/ $currencyType = $_GET['currency'] /*--}}
                                                @endif
                                    @if($packages['package']['id'] == $package_id)
                                        @if($is_expire)
                                            <a class="btn btn-lg btn-block btn-success" href="{{ route('client.admin.upgrade.package', $packages['package']['id'])}}">RENEW SUBSCRIPTION</a>
                                        @else
                                            <label class="btn btn-lg btn-block btn-success no-radius" style="cursor: unset;">CURRENT SUBSCRIBE</label>
                                        @endif
                                    @elseif($packages['package']['id'] > $package_id)
                                        <a class="btn btn-lg btn-block btn-info" href="{{($is_expire)? 'javascript:void(0)': route('client.admin.upgrade.package', $packages['package']['id'])}}" {{($is_expire)? 'disabled': ''}}>UPGRADE</a>                                
                                        <input type="hidden" name="h_package_id" value="$packages['package']['id']">
                                    @endif

                                </div>
                            </div>
                        </div>                        
                        <!-- /item -->
                            @endforeach
                    </div>
                </section>
            {!! Form::close() !!}
            </div>
            <hr>
            <div class="row">
                
            </div>
        </div>

        <div class="row">
            <div class="content-history">
                    <div class="col-lg-12">
                        <h4>Package history</h4>
                    </div>
                    <div class="col-lg-12">
                        
                    
                    <table class="table table-striped">
                        <tr>
                            <th>Package Name</th>
                            <th>Subscribe Date</th>
                            <th>Expired Date</th>
                        </tr>
                        @foreach($history as $his)
                        <tr>
                        @if($his->is_current == 1)
                            <td style="background-color:#83CB83;">{{$his->name}}</td>
                            <td style="background-color:#83CB83;">{{date('d-M-Y', strtotime($his->start_date))}}</td>
                            <td style="background-color:#83CB83;">{{date('d-M-Y', strtotime($his->expire_date))}}</td>
                        @else
                            <td>{{$his->name}}</td>
                            <td>{{date('d-M-Y', strtotime($his->start_date))}}</td>
                            <td>{{date('d-M-Y', strtotime($his->expire_date))}}</td>
                        @endif
                        </tr>
                        @endforeach
                    </table>
                    </div>
            </div>
        </div>
        
	</section>
@stop

@section('style')
	{!! Html::style('css/client/package.css') !!}

@stop


@section('script')

<script type="text/javascript">
    $('.btn-unsubscribed').click(function(e){
        e.preventDefault();
        if(confirm('Do you want to cancel your current subscription?')){
            window.location.href = $(this).attr('href');
        }else{
            return false;
        }
    });
    $('.active').removeClass('active');
    $('#menu-account-info').addClass('active');
    $('#package-info1').addClass('active');
</script>
@stop
