@extends($app_template['backend'])

@section('content')
 <section class="content">
 	 <h1>Add module to The Package</h1>
 	{!! Form::open(['url'=>'admin/packages/']) !!}                  
            <div class="form-group">
            	 {!! Form::text('name', '', array('class'=>'form-control')) !!} 
            </div>
            {!! Form::submit('Add', array('class'=>'btn btn-info')) !!}              
    {!! Form::close() !!}
 </section>
@stop