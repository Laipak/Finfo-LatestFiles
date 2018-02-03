@extends($app_template['client.backend'])
@section('title')
Media Access
@stop
@section('content')
    <section class="content" id="press-release">
        <div class="row head-search">
            <div class="col-sm-6">
                <lable class="label-title">Media Approval</lable>
            </div>
        </div>
        <div class="row">
            @if(Session::has('global'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('global') }}
                </div>
            @endif
            <div class="col-md-12">
                <div class="box">
                    <div id="box-user" class="box-body">
                        <table id="table-user" class="table table-bordered table-striped">
                            <thead>
                            <tr class="table-header">
                                <th class="hid"><input class="check-all" type="checkbox"></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th>Quick Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                           
                                <tr>
                                    <td width="15px" class="text-center"><input class="check-user" type="checkbox"></td>
                                    <td>John Doe</td>
                                    <td>123@gmail.com</td>
                                    <td>99112223344</td>
                                    <td><a data-toggle="modal" data-target="#permissionModal" data-whatever="@app1">Approve</a> | <a>Reject</a> &nbsp <a href="#"><i class="fa fa-trash-o fa-lg"></i></a></td>
                                </tr>
                                <tr>
                                    <td width="15px" class="text-center"><input class="check-user" type="checkbox"></td>
                                    <td>John Doe</td>
                                    <td>123@gmail.com</td>
                                    <td>99112223344</td>
                                    <td><a data-toggle="modal" data-target="#permissionModal" data-whatever="@app1">Approve</a> | <a>Reject</a> &nbsp <a href="#"><i class="fa fa-trash-o fa-lg"></i></a></td>
                                </tr>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="input-group input-group-sm">
                    <hr>
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-delete">REJECT SELECTED</button>
                    </span>
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-export">APPROVE SELECTED</button>
                    </span>
                </div>
            </div>
        </div>

    </section>
<!-- Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Set Access Permission</h4>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Company</label>
            <select class="form-control">
                <option>Campany 1</option>
                <option>Campany 2</option>
                <option>Campany 3</option>
                <option>Campany 4</option>
            </select>
          </div>
          <div class="form-group">
            <label for="recipient-name" class="control-label">Media Category</label>
            <div class="checkbox">
               <label>
                  <input type="checkbox" checked="checked">Categoary 1
               </label>
            </div>
            <div class="checkbox">
               <label>
                  <input type="checkbox">Categoary 2
               </label>
            </div>
            <div class="checkbox">
               <label>
                  <input type="checkbox">Categoary 3
               </label>
            </div>
           
          <div class="form-group">
            <label for="message-text" class="control-label">Media File</label>
            <div class="checkbox">
               <label>
                  <input type="checkbox" checked="checked">Categoary 1
               </label>
            </div>
            <div class="checkbox">
               <label>
                  <input type="checkbox">Categoary 2
               </label>
            </div>
            <div class="checkbox">
               <label>
                  <input type="checkbox">Categoary 3
               </label>
            </div>

          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-export">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

@stop

@section('style')
    {!! Html::style('css/package/press-release.css') !!}
    {!! Html::style('css/dataTables.bootstrap.min.css') !!}
    <style type="text/css">
        hr {
            border-top: 1px solid #CBC7C7;
        }
        .btn-export,
        .btn-export {
            background-color:#6aa501;
        }

        .btn-delete {
                background-color: #A4A2A2;
        }

        .btn {
            margin-left: 10px !important;
            color: white;
            border-radius: 0px !important;
        }

         #press-release table tr td a {
            cursor: pointer;
        }
    </style>
@stop
@section('script')

{!! Html::script('js/jquery.dataTables.min.js') !!}
{!! Html::script('js/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
    
    $( window ).resize(function() {
        var screen = $(window).width();
        if(screen < 770){
            $('#box-user').addClass('table-responsive');
        }else{
            $('#box-user').removeClass('table-responsive');
        }
    });

    $(document).ready(function(){
        $("#table-user").dataTable({
            aoColumnDefs: [
                  {
                     bSortable: false,
                     aTargets: [ 0, 3 ]
                  }
                ]
        });
    });

    $("#table-user").on("click",".check-all:checked",function(){
        $(".check-user:checkbox:not(:checked)").click(); 
    });

    $('.check-all:not(:checked)').click(function(){
        
        $(".check-user:checkbox:checked").click(); 
    });


</script>

@stop