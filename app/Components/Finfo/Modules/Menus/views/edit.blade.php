@extends($app_template['backend'])

@section('content')
    <section class="content" id="list-menus">
        <div class="row head-search">
            <div class="col-sm-6">
                <h2 style="margin:0;">Edit Menu</h2>
            </div>
        </div>
        <div class="row">
        <div class="col-sm-6">
            <div class="box">
                <div class="box-body">
                    {!! Form::open(array('route' => ['finfo.menus.backend.store', $data->id], 'id' => 'frm_add_menu')) !!}
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            {!! Form::label('title', 'Title') !!}
                            {!! Form::text('title', $data->title, ['class' => 'form-control', 'placeholder' => 'Title']) !!}
                            {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
                        </div>
                        <div class="form-group{{ $errors->has('link') ? ' has-error' : '' }}">
                            {!! Form::label('link', 'URL: ') !!}
                             <select name="link" class ='form-control'>
                                @foreach( $pageData as $page)
                                    @if($data->link == $page->name)
                                        <option value="{{$page->name}}" selected='selected'>{{$page->title}}</option>
                                    @else
                                        <option value="{{$page->name}}">{{$page->title}}</option>
                                    @endif
                                @endforeach
                            </select>
                            {!! $errors->first('link', '<span class="help-block">:message</span>') !!}
                        </div>
                        <div class="form-group{{ $errors->has('ordering') ? ' has-error' : '' }}">
                            {!! Form::label('ordering', 'Order: ') !!}
                            {!! Form::text('ordering', $data->ordering, ['class' => 'form-control']) !!}
                            {!! $errors->first('ordering', '<span class="help-block">:message</span>') !!}
                        </div>
                        <div class="form-group" style="margin-top:30px;">
                            {!! Form::submit('Update', ['class' => 'btn btn-primary btn-save']) !!}
                            <a href="{{route('finfo.menus.backend.list')}}" class='btn btn-danger btn-overwrite-cancel'>Cancel</a>
                        </div>
                       
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/finfo/customize.css') !!}
    <style type="text/css">
        .error{
            color: red;
            font-weight: 500;
        }
    </style>
@stop
@section('script')
<script type="text/javascript">
    $(document).ready(function(){
         $("#frm_add_menu").validate({
            rules: {
                'title':{required:   true},
                'link':{required:   true}
            },
            submitHandler: function(form) {
                form.submit();
            }
         });
    });
</script>
    {!! Html::script('js/jquery.validate.min.js') !!}
@stop
