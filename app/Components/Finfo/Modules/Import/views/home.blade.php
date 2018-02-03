@extends($app_template['backend'])


@section('content')



<div class="container">
		<form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('admin/importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data"> 
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="file" name="import_file" />
			<button class="btn btn-primary">Import File</button>
		</form>
	</div>

@stop