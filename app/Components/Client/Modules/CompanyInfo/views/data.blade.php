@extends($app_template['client.frontend'])
@section('content')
 <section class="content" id="leadership">
              
          
                <div class="row main-title">
                    <div class="col-md-12">
                         {!! $data['title']!!}
                    </div>
                </div>
       {!!html_entity_decode($data['body']) !!} 
    </section>

@stop
