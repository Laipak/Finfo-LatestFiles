@extends($app_template['client.backend'])
@section('title')
Menu
@stop
@section('content')



<div class="box-body">
 <form class="form-horizontal order-imp" method="POST" action="{{route('client.webpage.backend.postpages')}}"  >
      <ul class="list-group">
      @foreach($menu_per as $page)
      @if($page->module_id != 6)
        <li class="list-group-item">
            <label>
              <input type="checkbox" class="js-switch checkChange{{$page->module_id}}"  name="checkbox[]" 
              value="{{$page->module_id}}" 
              <?php if (in_array($page->module_id,$menu_pers))
              {
              echo 'checked="true"';
              } 
              ?>
              >
              {{ $page->name }}
            </label>
              @if($page->module_id === 1)
           <!--    <ul <?php if (!in_array($page->module_id,$menu_pers))
              {
              echo 'class="js-switch"';
              } else
              {
              echo 'class="js-switch"';
              } 
              ?>>
              @foreach($menu_per as $page)
              @if($page->id === 6)
               <li class="list-group-item">
                  <label>
                    <input type="checkbox" class="checkSwitch"  name="checkbox[]" 
                    value="{{$page->id}}" 
                    <?php if (in_array($page->module_id,$menu_pers))
                    {
                    echo 'checked="true"';
                    } 
                    ?>
                    >
                    {{ $page->name }}
                  </label>
              </li> 
            @endif
            @endforeach
            </ul>-->
            @endif

      @endif
      @endforeach
      </ul>
     <input type="hidden" name="company_id" value="{{ \Auth::user()->company_id }}">
     <input type="hidden" name="_token" value="{{ csrf_token() }}">
     <input class="btn btn-success btn-save" type="submit" value="Save">
      
</form>    
  </div>

@stop


      
@section('script')

<script type="text/javascript">
 
 <?php foreach($menu_per as $page){ 
     
    if($page->module_id == 1){ ?>
    var elem = document.querySelector('.checkChange1');
    var init = new Switchery(elem); 
    <?php }
    
    if($page->module_id == 2){ ?>
    var elem = document.querySelector('.checkChange2');
    var init = new Switchery(elem); 
    <?php } 
    
    if($page->module_id == 3){ ?>
    var elem = document.querySelector('.checkChange3');
    var init = new Switchery(elem); 
    <?php } 
    
    if($page->module_id == 4){ ?>
    var elem = document.querySelector('.checkChange4');
    var init = new Switchery(elem); 
    <?php } 
   
    
    if($page->module_id == 7){ ?>
    var elem = document.querySelector('.checkChange7');
    var init = new Switchery(elem); 
    <?php }
    
    if($page->module_id == 8){ ?>
    var elem = document.querySelector('.checkChange8');
    var init = new Switchery(elem); 
    <?php }
    
    if($page->module_id == 9){ ?>
    var elem = document.querySelector('.checkChange9');
    var init = new Switchery(elem); 
    <?php }
    
    if($page->module_id == 10){ ?>
    var elem = document.querySelector('.checkChange10');
    var init = new Switchery(elem); 
    <?php }
    
    if($page->module_id == 11){ ?>
    var elem = document.querySelector('.checkChange11');
    var init = new Switchery(elem); 
    <?php }
    
    if($page->module_id == 12){ ?>
    var elem = document.querySelector('.checkChange12');
    var init = new Switchery(elem); 
    <?php }   ?>
    
    
     
 <?php } ?>
 

 
 
/* 	var elem = document.querySelector('.checkChange1');
    var init = new Switchery(elem); 
    
   	var elem = document.querySelector('.checkChange2');
    var init = new Switchery(elem); 
    
   	var elem = document.querySelector('.checkChange3');
    var init = new Switchery(elem); 
    
   	var elem = document.querySelector('.checkChange4');
    var init = new Switchery(elem);
    
    var elem = document.querySelector('.checkChange5');
    var init = new Switchery(elem); 
    
   	var elem = document.querySelector('.checkChange6');
    var init = new Switchery(elem);
    
    
   	var elem = document.querySelector('.checkChange7');
    var init = new Switchery(elem);
    
   	var elem = document.querySelector('.checkChange8');
    var init = new Switchery(elem);
    
    var elem = document.querySelector('.checkChange9');
    var init = new Switchery(elem); 
    
   	var elem = document.querySelector('.checkChange10');
    var init = new Switchery(elem);
    
   	var elem = document.querySelector('.checkChange11');
    var init = new Switchery(elem); 
    
   	var elem = document.querySelector('.checkChange12');
    var init = new Switchery(elem);
  */
  
  $('.checkChange').change(function(){
    if (this.checked) 
    {
       $(this).parents('li').find('ul').removeClass('hidden')
    }
    else
    {
      $(this).parents('li').find('ul').addClass('hidden')
    }
  }) 



/*
 $(".checkSwitch").bootstrapSwitch({
  onClass: 'success',
  offClass: 'default'
 });
 
*/ 

</script>
@stop