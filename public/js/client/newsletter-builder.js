$(function() { 
// Resize	
function resize(){
	$('.resize-height').height(window.innerHeight - 50);
	$('.resize-width').width(window.innerWidth - 250);
	//if(window.innerWidth<=1150){$('.resize-width').css('overflow','auto');}
	
	}
$( window ).resize(function() {resize();});
resize();

	
	
 
//Add Sections
$("#newsletter-builder-area-center-frame-buttons-add").hover(
  function() {
    $("#newsletter-builder-area-center-frame-buttons-dropdown").fadeIn(200);
  }, function() {
    $("#newsletter-builder-area-center-frame-buttons-dropdown").fadeOut(200);
  }
);

$("#newsletter-builder-area-center-frame-buttons-dropdown").hover(
  function() {
    $(".newsletter-builder-area-center-frame-buttons-content").fadeIn(200);
  }, function() {
    $(".newsletter-builder-area-center-frame-buttons-content").fadeOut(200);
  }
);


$("#add-header").hover(function() {
    $(".newsletter-builder-area-center-frame-buttons-content-tab[data-type='header']").show()
	$(".newsletter-builder-area-center-frame-buttons-content-tab[data-type='content']").hide()
	$(".newsletter-builder-area-center-frame-buttons-content-tab[data-type='footer']").hide()
  });
  
$("#add-content").hover(function() {
    $(".newsletter-builder-area-center-frame-buttons-content-tab[data-type='header']").hide()
	$(".newsletter-builder-area-center-frame-buttons-content-tab[data-type='content']").show()
	$(".newsletter-builder-area-center-frame-buttons-content-tab[data-type='footer']").hide()
  });
  
$("#add-footer").hover(function() {
    $(".newsletter-builder-area-center-frame-buttons-content-tab[data-type='header']").hide()
	$(".newsletter-builder-area-center-frame-buttons-content-tab[data-type='content']").hide()
	$(".newsletter-builder-area-center-frame-buttons-content-tab[data-type='footer']").show()
  });   
  
  
  
 $(".newsletter-builder-area-center-frame-buttons-content-tab").hover(
  function() {
    $(this).append('<div class="newsletter-builder-area-center-frame-buttons-content-tab-add"><i class="fa fa-plus"></i>&nbsp;Insert</div>');
	$('.newsletter-builder-area-center-frame-buttons-content-tab-add').click(function() {

	$("#newsletter-builder-area-center-frame-content").prepend($("#newsletter-preloaded-rows .sim-row[data-id='"+$(this).parent().attr("data-id")+"']").clone());
	hover_edit();
	perform_delete();
	$("#newsletter-builder-area-center-frame-buttons-dropdown").fadeOut(200);
		})
  }, function() {
    $(this).children(".newsletter-builder-area-center-frame-buttons-content-tab-add").remove();
  }
); 
  
$('#sim-edit-image #image-file').change(function(){
	img_id = $('#path').val();

	var old_img = $('#'+img_id).attr("src");
	$('#old-img').val(old_img.replace(baseUrl+'/', ""));

		var file = this.files[0];
	    var imagefile = file.type;

	    var match = ["image/jpeg", "image/png", "image/jpg"];
	    // validate file extension
	    if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
	        alert('Please select image file.');
	        $('#image-file').val('');
	        return false;
	    } else {
	        var formData = new FormData($('#frm-image')[0]);
	        $.ajax({
	            url: baseUrl + '/admin/newsletter-broadcast/upload-img',
	            processData: false,
	            contentType: false,
	            type: "POST",
	            data: formData,
	            success: function(data) {
	                //console.log(data);
	                $('#'+img_id).attr("src", baseUrl +'/'+ data);
	            },
	        });
	    }
});

  
//Edit
function hover_edit(){


$(".sim-row-edit").hover(
  function() {
    
    if($(this).attr("data-type") == 'image' && $(this).attr("data-img") == 'banner'){
    	$(this).append('<div class="sim-row-edit-hover"><span> 720 x 250</span><i class="fa fa-upload i-left"></i></div>');
    }else if($(this).attr("data-type") == 'image'){
    	$(this).append('<div class="sim-row-edit-hover"><i class="fa fa-pencil i-right"></i><i class="fa fa-upload i-left"></i></div>');
    }else{
    	$(this).append('<div class="sim-row-edit-hover"><i class="fa fa-pencil" style="line-height:30px;"></i></div>');
    }
	$(".sim-row-edit-hover").click(function(e) {e.preventDefault()})
	$(".sim-row-edit-hover .fa-pencil").click(function(e) {
		e.preventDefault();
		big_parent = $(this).parent().parent();
		
		//edit image
		if(big_parent.attr("data-type")=='image'){
		
		
		$("#sim-edit-image #select-position").val(big_parent.children('img').attr("align"));
		$("#sim-edit-image #img-size").val(big_parent.attr("width").replace('%', ''));
		$("#sim-edit-image").fadeIn(500);
		$("#sim-edit-image .sim-edit-box").slideDown(500);
		
		$("#sim-edit-image .sim-edit-box-buttons-save").click(function() {
		  $(this).parent().parent().parent().fadeOut(500)
		  $(this).parent().parent().slideUp(500)
		  	var other_width = 98 - $("#sim-edit-image #img-size").val();
		  	var current_position = $("#sim-edit-image #select-position").val();
		  	var	other_position = 'right';
		  	if(current_position == 'right'){
		  		other_position = 'left';
		  	}
		  	big_parent.children('img').attr("align", current_position);

		  	big_parent.attr("width", $("#sim-edit-image #img-size").val());
		  	big_parent.attr("style","width: "+ $("#sim-edit-image #img-size").val()+ '% !important;display: inline-table;');
		  	big_parent.next().attr("style","width: "+ other_width + '% !important;display: inline-table;');

		  	var old_class_first = big_parent.attr('style').replace('float: right;', '').replace('float: left;', '');
		  	var old_class_other = big_parent.next().attr('style').replace('float: right;', '').replace('float: left;', '');

		  	big_parent.attr('style', old_class_first+'float: '+current_position+';');
		  	big_parent.next().attr('style', old_class_other+'float: '+other_position+';');
		   });

		}
	
	

	
		//edit link
		if(big_parent.attr("data-type")=='link'){
		
		//$("#sim-edit-link .title").val(big_parent.text());
		$("#sim-edit-link .url").val(big_parent.attr("href"));
		$("#sim-edit-link").fadeIn(500);
		$("#sim-edit-link .sim-edit-box").slideDown(500);
		
		$("#sim-edit-link .sim-edit-box-buttons-save").click(function() {
		  $(this).parent().parent().parent().fadeOut(500)
		  $(this).parent().parent().slideUp(500)
		   
		    //big_parent.text($("#sim-edit-link .title").val());
			big_parent.attr("href",$("#sim-edit-link .url").val());

			});

		}
		
		//edit title
		
		if(big_parent.attr("data-type")=='title'){

		$("#sim-edit-title .title").val(big_parent.text());
		$("#sim-edit-title").fadeIn(500);
		$("#sim-edit-title .sim-edit-box").slideDown(500);
		
		$("#sim-edit-title .sim-edit-box-buttons-save").click(function() {
			if($("#sim-edit-title .title").val() == ''){
				$('#title-error').html('This field required.');
				return false;
			}else{
				$('#title-error').html('');
			}
		  $(this).parent().parent().parent().fadeOut(500)
		  $(this).parent().parent().slideUp(500)
		   
		    big_parent.text($("#sim-edit-title .title").val());

			});

		}
		
		//edit text
		if(big_parent.attr("data-type")=='text'){
		
		$("#sim-edit-text .text").val(big_parent.text());
		$("#sim-edit-text").fadeIn(500);
		$("#sim-edit-text .sim-edit-box").slideDown(500);
		
		$("#sim-edit-text .sim-edit-box-buttons-save").click(function() {
			if($("#sim-edit-text .text").val() == ''){
				$('#text-error').html('This field required.');
				return false;
			}else{
				$('#text-error').html('');
			}
		  $(this).parent().parent().parent().fadeOut(500)
		  $(this).parent().parent().slideUp(500)
		   
		    big_parent.text($("#sim-edit-text .text").val());
			
			});

		}
		
		//edit icon
		if(big_parent.attr("data-type")=='icon'){
		$('.icon-active').removeClass('icon-active');
		$("#sim-edit-icon .url").val(big_parent.attr("href"));
		
		$("#sim-edit-icon").fadeIn(500);
		$("#sim-edit-icon .sim-edit-box").slideDown(500);
		
		$("#sim-edit-icon i").click(function() {
			$('.icon-active').removeClass('icon-active');
		  	$(this).addClass('icon-active');
		    big_parent.children('i').attr('class',$(this).attr('class').replace('icon-active', ''));
			});

		$("#sim-edit-icon .sim-edit-box-buttons-save").click(function() {
			$(this).parent().parent().parent().fadeOut(500)
		  	$(this).parent().parent().slideUp(500)
		  	big_parent.attr("href",$("#sim-edit-icon .url").val());
		});

		}//



		
	});

	//edit img
	$(".sim-row-edit-hover .fa-upload").click(function(e) {
		e.preventDefault();
		big_parent = $(this).parent().parent();

	
		//edit image
		if(big_parent.attr("data-type") == 'image'){
			$('#sim-edit-image #image-file').click();
			$('#path').val(big_parent.children('img').attr('id'));
		}
	});


  }, function() {
    $(this).children(".sim-row-edit-hover").remove();
  }
);
}
hover_edit();


//close edit
$(".sim-edit-box-buttons-cancel").click(function() {
  $(this).parent().parent().parent().fadeOut(500)
   $(this).parent().parent().slideUp(500)
});
   


//Drag & Drop
$("#newsletter-builder-area-center-frame-content").sortable({
  revert: true
});
	

$(".sim-row").draggable({
      connectToSortable: "#newsletter-builder-area-center-frame-content",
      //helper: "clone",
      revert: "invalid",
	  handle: ".sim-row-move"
});



//Delete
function add_delete(){
	$(".sim-row").append('<div class="sim-row-delete"><i class="fa fa-times" ></i></div>');
	
	}
add_delete();


function perform_delete(){
$(".sim-row-delete").click(function() {
  $(this).parent().remove();
});
}
perform_delete();




//Download
 $("#newsletter-builder-sidebar-buttons-abutton").click(function(){
	 
	$("#newsletter-preloaded-export").html($("#newsletter-builder-area-center-frame-content").html());
	$("#newsletter-preloaded-export .sim-row-delete").remove();
	$("#newsletter-preloaded-export .sim-row").removeClass("ui-draggable");
	$("#newsletter-preloaded-export .sim-row-edit").removeAttr("data-type");
	$("#newsletter-preloaded-export .sim-row-edit").removeClass("sim-row-edit");
	
	export_content = $("#newsletter-preloaded-export").html();
	
	$("#export-textarea").val(export_content)
	$( "#export-form" ).submit();
	$("#export-textarea").val(' ');
	 
});
	 
	 
//Export 
$("#newsletter-builder-sidebar-buttons-bbutton").click(function(){
	
	$("#sim-edit-export").fadeIn(500);
	$("#sim-edit-export .sim-edit-box").slideDown(500);
	
	$("#newsletter-preloaded-export").html($("#newsletter-builder-area-center-frame-content").html());
	$("#newsletter-preloaded-export .sim-row-delete").remove();
	$("#newsletter-preloaded-export .sim-row").removeClass("ui-draggable");
	$("#newsletter-preloaded-export .sim-row-edit").removeAttr("data-type");
	$("#newsletter-preloaded-export .sim-row-edit").removeClass("sim-row-edit");
	
	preload_export_html = $("#newsletter-preloaded-export").html();
	$.ajax({
	  url: "_css/newsletter.css"
	}).done(function(data) {

	
export_content = '<style>'+data+'</style><link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic" rel="stylesheet" type="text/css"><link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css"><div id="sim-wrapper"><div id="sim-wrapper-newsletter">'+preload_export_html+'</div></div>';
	
	$("#sim-edit-export .text").val(export_content);
	
	
	});
	
	
	
	$("#newsletter-preloaded-export").html(' ');
	
	});


	$('.link-i').click(function(e){
		e.preventDefault();
	});

});