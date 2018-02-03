@extends($app_template['client.frontend'])

@section('content')
@if ($active_theme == "default")
<section class="content">
<div class="row title" id="email-alert">
    <div class="col-md-12">
    	Email Alerts
    </div>
</div>


<div class="row">
	<div class="col-sm-3 col-md-3 left-col">
		<p class="description">To keep yourself updated with {{ ucfirst(Session::get('company_name')) }} latest updates, please fill in the form.</p>
	</div>

	<div class="col-sm-9 col-md-9 right-col">
		@if(Session::has('global'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global') }}
            </div>
        @endif
		{!! Form::open(array('route' => 'package.post.email-alerts', 'id' => 'frm-ir-calendar', 'class'=> 'form-horizontal')) !!}
			<div class="row">
				<div class="col-md-10 content-email-alert">
					<p><span>*</span>Required.</p>
					<div class="form-group">
	                    <label class="control-label col-sm-3 label-txt" for="name">Name</label>
	                    <div class="col-sm-9 col-md-6 required">
	                      <input name="name" type="text" value="{{Input::old('name')}}" class="form-control">
	                      {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label class="control-label col-sm-3 label-txt" for="email">Email</label>
	                    <div class="col-sm-9 col-md-6 required">
	                      <input name="email" type="text" value="{{Input::old('email')}}" class="form-control">
	                      {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
	                    </div>
	                </div>

	                <div class="form-group">
                            <label class="control-label col-sm-3" for="password">Category</label>
                            <div class="col-sm-6">
	                            @foreach($modules as $cat)
	                            @if($cat->name != 'Email Alerts' && $cat->name != 'Media Access')
		                            <div class="checkbox">
		                               <label>
		                                  <input type="checkbox" name="category[]" value="{{$cat->id}}">{{$controller->getNavigationByRouteName($cat->route_name, $cat->name )}}
		                               </label>
		                            </div>
	                            @endif
	                            @endforeach
	                            {!! $errors->first('category', '<span class="help-block">:message</span>') !!}
                            	{{ (isset($error_recaptcha))? $error_recaptcha : ''}}
        					</div>
                    </div>               
	               
	                <div class="form-group">
	                    <label class="control-label col-sm-3" for="email"></label>
	                    <div class="col-sm-6">
	                      <div class="g-recaptcha" data-sitekey="6Lf82hITAAAAAN6hMMyd-v6sm1tR1dLW7a4RaZQp"></div>
	                      {!! $errors->first('g-recaptcha-response', '<span class="help-block">:message</span>') !!}
	                    </div>

	                </div>
	
	                <br><br>
	                <div class="form-group">
	                    <label class="control-label col-sm-3" for="email"></label>
	                    <div class="col-sm-5">
	                      <input type="submit" class="btn btn-customize" value="Submit">
	                    </div>
	                </div>
	                <div class="form-group">
	                    <label class="control-label col-sm-6" for="email">To unsubscribe, click <a href="{{route('package.email-alerts.unsubscribe')}}">here</a>.</label>
	                    <div class="col-sm-6">
	                      
	                    </div>
	                </div>
				</div>
			</div>

		</form>
	</div>
</div>
 
</section>
@else
    <h2>Email Alerts</h2>
    <div class="row">
    	<div class="col-md-3"></div>
    	<div class="col-md-6">
    		{!! Form::open(array('route' => 'package.post.email-alerts', 'id' => 'frm-ir-calendar', 'class'=> 'form-horizontal')) !!}
				<p>To keep  yourself updated with Patties Food latest updates, please fill in the form.</p>
				<div class="form-group">
	                <label for="name">Name</label>
	                <input name="name" type="text" value="{{Input::old('name')}}" class="form-control">
	                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
	            </div>
	            <div class="form-group">
	                <label for="email">Email</label>
	                <input name="email" type="text" value="{{Input::old('email')}}" class="form-control">
                    {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
	            </div>

	            <div class="form-group">
	            	<label for="category">Category</label>
	            	@foreach($modules as $cat)
                    @if($cat->name != 'Email Alerts' && $cat->name != 'Media Access')
                        <div class="checkbox">
                           <label>
                              <input type="checkbox" name="category[]" value="{{$cat->id}}">{{$controller->getNavigationByRouteName($cat->route_name, $cat->name )}}
                           </label>
                        </div>
                    @endif
                    @endforeach
                    {!! $errors->first('category', '<span class="help-block">:message</span>') !!}
                	{{ (isset($error_recaptcha))? $error_recaptcha : ''}}
	            </div>
				
				<div class="form-group">
					<div class="g-recaptcha" data-sitekey="6Lf82hITAAAAAN6hMMyd-v6sm1tR1dLW7a4RaZQp"></div>
	                {!! $errors->first('g-recaptcha-response', '<span class="help-block">:message</span>') !!}
				</div>

	            <button type="submit" class="btn btn-success">Submit</button>
	            <h3>&nbsp;</h3>
	            <div class="form-group">
                    <label for="email">To unsubscribe, click <a href="{{route('package.email-alerts.unsubscribe')}}">here</a>.</label>
                    <div></div>
                </div>
    		</form>
    	</div>
    </div>
@endif

@stop
@section('seo')
    <title>{{ucfirst(Session::get('company_name'))}} | Email alerts</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
@stop
@section('style')
@if ($active_theme == "default")
	{!! Html::style('css/package/general-style.css') !!}
	<style type="text/css">
	.error, .help-block{ 
		color: red;
	}
	.label-txt {
		padding-top: 11px !important;
	}
	.main-footer{
		border-top: 1px solid #DFDFDF;
	}
	.top-content {
	    padding-bottom: 0px;
	}
	</style>
@endif
@stop

@section('script')

<script type="text/javascript">
    $('.menu-active').removeClass('menu-active');
    $('#email-alerts').addClass('menu-active');
</script>

@stop