@extends($app_template['client.backend'])

@section('content')
	<section class="content">
		<form action="">
			<div class="row" id="finan-highlight">
				<div class="col-md-12">
				<h4 class="heading">Add New Financial Highlights</h4>
				<h4>Select Financial Term</h4>
				<BR>
				<div class="row">
					<div class="col-md-3">
						<h5>Select Quarter</h5>
						<select name="" id="input" class="form-control" required="required">
							<option value="">Full Year</option>
						</select>
					</div>
					<div class="col-md-3">
						<h5>Select Year</h5>
						<select name="" id="input" class="form-control" required="required">
							<option value="">2014</option>
						</select>
					</div>
				</div>
				<BR>

				<h4>Insert Title</h4>
				<div class="row">
				<div class="input_fields_wrap">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-4">
								<input type="text" name="title[]" id="input" class="form-control">
							</div>
						</div>
					</div>					
					
				</div> <!-- end input_fields_wrap -->

				</div> <!-- end insert results -->
				<div class="row">
					<div class="col-md-2">
						<a class="add_field_button" href="">
							<div><i class="fa fa-plus"></i></div>
						</a>
					</div>
				</div>

		</div><!--  end main row -->	
		</form>
	</section>
@stop

@section('style')
	{!! Html::style('css/client/finan-highlight.css') !!}
@stop
@section('script')
	<script type="text/javascript">
		(function($) {

		// Add more field
	    var max_fields      = 10; //maximum input boxes allowed
	    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
	    var add_button      = $(".add_field_button"); //Add button ID

	    var x = 1; //initlal text box count
	    $(add_button).click(function(e){ //on add input button click
	        e.preventDefault();
	        if(x < max_fields){ //max input box allowed
	            x++; //text box increment
	            $(wrapper).append('<div class="col-md-12"><div class="row"><div class="col-md-4"><input type="text" name="title[]" id="input" class="form-control"></div><a href="#" class="remove_field" title="remove"><i class="fa fa-trash-o fa-lg" style="color:red"></i></a></div></div>'); //add input box
	        }
	    });

	    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
	        e.preventDefault(); $(this).parent('div').parent('div').remove(); x--;
	    });


		  // Browser supports HTML5 multiple file?
		  var multipleSupport = typeof $('<input/>')[0].multiple !== 'undefined',
		      isIE = /msie/i.test( navigator.userAgent );

		  $.fn.customFile = function() {

		    return this.each(function() {

		      var $file = $(this).addClass('custom-file-upload-hidden'), // the original file input
		          $wrap = $('<div class="form-group">'),
		          $input = $('<div class="col-md-3"><input type="text" class="file-upload-input form-control col-md-3" /></div>'),
		          // Button that will be used in non-IE browsers
		          $button = $('<div class="col-md-3"><button type="button" class="btn">Select File</button></div>'),
		          // Hack for IE
		          $label = $('<label class="btn" for="'+ $file[0].id +'">Select  File</label>');

		      // Hide by shifting to the left so we
		      // can still trigger events
		      $file.css({
		        position: 'absolute',
		        left: '-9999px'
		      });

		      $wrap.insertAfter( $file )
		        .append( $file, $input, ( isIE ? $label : $button ) );

		      // Prevent focus
		      $file.attr('tabIndex', -1);
		      $button.attr('tabIndex', -1);

		      $button.click(function () {
		        $file.focus().click(); // Open dialog
		      });

		      $file.change(function() {

		        var files = [], fileArr, filename;

		        // If multiple is supported then extract
		        // all filenames from the file array
		        if ( multipleSupport ) {
		          fileArr = $file[0].files;
		          for ( var i = 0, len = fileArr.length; i < len; i++ ) {
		            files.push( fileArr[i].name );
		          }
		          filename = files.join(', ');

		        // If not supported then just take the value
		        // and remove the path to just show the filename
		        } else {
		          filename = $file.val().split('\\').pop();
		        }

		        $input.val( filename ) // Set the value
		          .attr('title', filename) // Show filename in title tootlip
		          .focus(); // Regain focus

		      });

		      $input.on({
		        blur: function() { $file.trigger('blur'); },
		        keydown: function( e ) {
		          if ( e.which === 13 ) { // Enter
		            if ( !isIE ) { $file.trigger('click'); }
		          } else if ( e.which === 8 || e.which === 46 ) { // Backspace & Del
		            // On some browsers the value is read-only
		            // with this trick we remove the old input and add
		            // a clean clone with all the original events attached
		            $file.replaceWith( $file = $file.clone( true ) );
		            $file.trigger('change');
		            $input.val('');
		          } else if ( e.which === 9 ){ // TAB
		            return;
		          } else { // All other keys
		            return false;
		          }
		        }
		      });

		    });

		  };

		  // Old browser fallback
		  if ( !multipleSupport ) {
		    $( document ).on('change', 'input.customfile', function() {

		      var $this = $(this),
		          // Create a unique ID so we
		          // can attach the label to the input
		          uniqId = 'customfile_'+ (new Date()).getTime(),
		          $wrap = $this.parent(),

		          // Filter empty input
		          $inputs = $wrap.siblings().find('.file-upload-input')
		            .filter(function(){ return !this.value }),

		          $file = $('<input type="file" id="'+ uniqId +'" name="'+ $this.attr('name') +'"/>');

		      // 1ms timeout so it runs after all other events
		      // that modify the value have triggered
		      setTimeout(function() {
		        // Add a new input
		        if ( $this.val() ) {
		          // Check for empty fields to prevent
		          // creating new inputs when changing files
		          if ( !$inputs.length ) {
		            $wrap.after( $file );
		            $file.customFile();
		          }
		        // Remove and reorganize inputs
		        } else {
		          $inputs.parent().remove();
		          // Move the input so it's always last on the list
		          $wrap.appendTo( $wrap.parent() );
		          $wrap.find('input').focus();
		        }
		      }, 1);

		    });
		  }

}(jQuery));

$('input[type=file]').customFile();

	</script>
@stop
