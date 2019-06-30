function inputReturn(target) {
	jQuery(target + ' input').keypress( 
		function(event){ 
			if (event.keyCode == '13') {
				var targetElement = jQuery(this).attr('data-return-action');
				if (targetElement != undefined) {
					jQuery('#' + targetElement).click();
	 				event.preventDefault();
				}
			}
		}
	);
}

jQuery(document).ready(function () {
	inputReturn('');
});