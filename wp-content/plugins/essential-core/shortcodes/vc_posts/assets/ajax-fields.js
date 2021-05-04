function addSelectValue(data,elements){
	var $ = jQuery,
		selectOptions;
	for (var k = 0; k < elements.length; k++) {

		var selectedValue = $(elements[k]).attr('data-option'),
			selectOptions = '',
			selectActive = '';

		for (var i = 0; i < data.length; i++) {
			if (selectedValue == data[i].meta_key || i == 0) {
				selectActive = 'selected="true"';
			}  else {
				selectActive = '';
			}
			selectOptions += '<option '+ selectActive +' class="' + data[i].meta_key + '" value="' + data[i].meta_key + '">' + data[i].label + '</option>';
		}

		$(elements[k]).html(selectOptions);

	}
}


function init_ajax_field() {

	var $ = jQuery;

	jQuery('.templates').on('change', function(){
		var this_val = $(this).val(); 
		if (this_val == 'filmstrip') { 
			$('[data-vc-shortcode-param-name="filter_position"]').removeClass( 'vc_dependent-hidden' );
		} else {
			$('[data-vc-shortcode-param-name="filter_position"]').addClass( 'vc_dependent-hidden' );
		}
	}).trigger('change');

	jQuery('.post_type').on('change', function(){
		var this_val = $(this).val();

		$.ajax({
			method: "POST",
			url: ajaxurl,
			dataType: "json",
			data: {
				action: "prague_get_pixfields",
				pix_category: this_val,
				_vcnonce: window.prague_nonce
			},
		    success: function(data){

		    	if (!data.length) return false;

		    	addSelectValue(data,
		    			['[name="filter_type"]','[name="filter_type_2"]','[name="filter_type_3"]']
		    		);

		    }

		});
	}).trigger('change');
}

