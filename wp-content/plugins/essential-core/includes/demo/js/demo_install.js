jQuery(function ($){

	function import_demo(){

		var inpt = $('.JS_demo_imports input:checked').first(),
			inpt_val = inpt.val(),
			loader = inpt.parent('label').next('.loader');

		
		loader.addClass('loading');

		if (inpt_val == undefined) {
			setTimeout(function(){
				wp.customize.previewer.refresh();
			}, 200);
			return false;
		}

		$.ajax({
			url  : essential_demo.ajaxurl,
			type : "POST",
			data : {
				'action' : 'essential_import_' + inpt_val,
				'wpnonce' : essential_demo.wpnonce,
			}
		}).done(function( data ) {
				inpt.prop("checked", false );
				loader.removeClass('loading');
				loader.addClass('complete');
				import_demo();
		}).fail(function( error ) {
			inpt.prop("checked", false );
			loader.removeClass('loading');
			loader.text( error );
			import_demo();

		});
	}

	/* 
	* Run Import Demo Data
	*/
	$('.JS_start_import').on('click', function(){
 		import_demo();
	});

	// no refresh page
	$('form#customize-controls').on('submit',function(){
		return false;
	});

});