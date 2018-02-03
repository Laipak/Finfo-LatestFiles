@extends($app_template['client.backend'])
@section('title')
Newsletter & Broadcast
@stop
@section('content')
    <section class="content" id="press-release">
        @if(Session::has('global'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global') }}
            </div>
        @endif
        @if(Session::has('global-danger'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                {{ Session::get('global-danger') }}
            </div>
        @endif
        <div class="row head-search">
            <div class="col-sm-6 title-mobile">
                <lable class="label-title">Subscription</lable>
            </div>
       </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div id="box-user" class="box-body">
                        <table id="table-user" class="table table-bordered table-striped">
                            <thead>
                                <tr class="table-header">
                                 <th>Email</th>
                                   
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($email_sl as $email)
                                <tr>
                                    <td>{{$email->email}}</td>
                                 </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <input type="hidden" id="url" value="{{route('package.admin.newsletter-broadcast.email-seed-list')}}">
        </form>
    </section>
@stop
@section('style')
    {!! Html::style('css/package/press-release.css') !!}
    {!! Html::style('css/dataTables.bootstrap.min.css') !!}
@stop


