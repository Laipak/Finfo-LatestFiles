@extends($app_template['backend'])

@section('content')
 <section class="content">
 <h1>Package List</h1>
 @foreach($packages as $package)
	<a href="{{ URL::to('admin/packages/'.$package['package']['id']) }}" class="btn btn-default">{{$package['package']['name']}}</a>
 @endforeach
 </section>
@stop