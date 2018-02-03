@extends($app_template['backend'])

@section('content')
    <section class="content" id="themes">
        @if (session()->has('successExtract'))
            <div class='row'>
                <div class="col-sm-12">
                    <div class='alert alert-success'> {{session('successExtract')}}</div>
                </div>
            </div>
        @elseif(session()->has('successUninstall'))
            <div class='row'>
                <div class="col-sm-12">
                    <div class='alert alert-danger'> {{session('successUninstall')}}</div>
                </div>
            </div>
        @elseif(session()->has('successActivateTheme'))
            <div class='row'>
                <div class="col-sm-12">
                    <div class='alert alert-danger'> {{session('successActivateTheme')}}</div>
                </div>
            </div>
         @elseif(session()->has('invalidInstallTheme'))
            <div class='row'>
                <div class="col-sm-12">
                    <div class='alert alert-danger'> {{session('invalidInstallTheme')}}</div>
                </div>
            </div>
            @if (isset($error_remove) && !empty($error_remove))
                <div class='row'>
                    <div class="col-sm-12">
                        @foreach($error_remove as $error_msg)
                            <div class='alert alert-danger'>{{$error_msg}}</div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
        <div class="row head-search">
            <div class="col-sm-12">
                <h2 style="margin:0;">Themes</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>Upload theme</label>
                    {!! Form::open(array('route' => 'package.admin.theme.upload',  'method' => 'post', 'files'=> true, 'id' => 'frm_install_theme' )) !!}
                    <div class="form-group">
                        <input type="file" class="hidden" id="themes_file" name="themes_file">
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-sm-12">
                                {!! Form::text('theme', null, ['class' => 'form-control upload-file', 'placeholder' => 'Support only (zip)', 'id' => 'theme_zip', 'readonly']) !!}
                            </div>
                            <div class="col-md-3 col-lg-2 col-sm-12">
                                <button type="button" class="btn btn-upload btn-warning upload-theme">Select File</button>
                            </div>
                            <div class="col-md-3 col-lg-2 col-sm-12 ">
                                <button type="submit" class="btn btn-danger btn-install-theme">Install theme</button>
                            </div>
                        </div>
                        {!! $errors->first('upload_file', '<span class="error-block">:message</span>') !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 wrap-lists-theme">
                @foreach($getThemeData as $theme)
                    <div class="col-sm-4">
                        <h4>{{$theme->theme_name}}</h4>
                        <div class='individual-theme'>
                            @if(isset($theme->thumnail_img) && !empty($theme->thumnail_img)) 
                                <img src="{{$theme->thumnail_img}}" alt="{{$theme->theme_name}}"/>
                            @endif 
                        </div>
                        <div class='theme-action'>
                            @if ($theme->status == 0)
                                <a href='{{route('package.admin.theme.activate', $theme->id)}}' title='Activate'>Activate</a> 
                            @else
                                <a href='{{route('package.admin.theme.de-activate', $theme->id)}}' title='Deactivate'>Deactivate</a> 
                            @endif
                            / <a href="{{route('package.admin.theme.uninstall', $theme->id)}}" title="Uninstall" class='text-danger'>Uninstall</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@stop
@section('style')
    {!! Html::style('css/dataTables.bootstrap.min.css') !!}
    {!! Html::style('css/finfo/theme.css') !!}
@stop

@section('script')
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
    $(function(){
        $('.upload-theme').click(function(){
            $('#themes_file').click();
        });
        $('#themes_file').change(function(e) {
            var file = this.files[0];
            var extension = file.name.replace(/^.*\./, '');
            if (!(extension == 'zip')) {
                alert('Please select zip file');
                $('#themes_file').val('');
                $('#theme_zip').val('');
                if ('function' == typeof pCallback) {
                    pCallback(false);
                }
                return;
            } else {
                $('#theme_zip').val(this.files[0].name);
            }
        });
    });
</script>
@stop
