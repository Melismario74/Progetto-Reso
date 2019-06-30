var fhFoto_data = {};


function fhFoto_capture() {
	
	var popupFoto = DMPopup.getInstance({
		name: 'popupFoto',
		includeCallback: function () {
			this.openPopup('open', '');
		}
	});
	
}