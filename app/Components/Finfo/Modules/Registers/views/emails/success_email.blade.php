@extends($app_template['frontend'])

@section('content')

	@if(session()->has('success'))
		<div class="alert alert-success">{{ session('success') }}</div>
	@endif

@stop