function fhVectors_search() {

	var tbodyElement = '#fhVectors_results_table tbody';
	var progressElement = '#fhVectors .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();

	jQuery.post(
		'index.php?controller=vector&task=jsonGetVectors&type=json',
		{
			
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
			
				if (result.result > 0) {
				
					vectors = result.vectors;
					vectorsCount = vectors.length;
					
					for (var i = 0; i < vectorsCount; i++) {
						var vectorData = vectors[i];
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center">' + vectorData.vector_id + '</td>' +
								'<td>' +
									'<a href="#" onclick="fhVectors_openVector(' + vectorData.vector_id + '); return false;">' + vectorData.name + '</a>' +
								'</td>' +
								'<td style="text-align: center">' +
									'<button class="btn btn-danger" onclick="fhVectors_deleteVector(' + vectorData.vector_id + '); return false;"><i class="icon-white icon-remove"></i></button>' +
								'</td>' +
							'</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
					
				}
				
				jQuery(progressElement).slideUp();
				jQuery(tbodyElement).slideDown();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery(progressElement).slideUp();
			}
		}
		
	);
	
}

function fhVectors_openVector(vectorId) {

	var params = 'vectorId=' + vectorId;
	
	var popupVector = DMPopup.getInstance({
		name: 'popupVector',
		includeCallback: function () {
			this.openPopup('open', params);
		}
	});

}

function fhVectors_deleteVector(vectorId) {
	
	if (confirm("Vuoi davvero eliminare il vettore?")) {
		jQuery.post(
			'index.php?controller=vector&task=jsonDeleteVector&type=json',
			{
				"vectorId": vectorId
			},
			function (data) {
				var result = DMResponse.validateJson(data);
				
				if ((result != false) && (result.result >= 0)) {	
							
					alert("Vettore eliminato");
					fhVectors_search();
									
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
				}
			}
			
		);
	}
	
}

function fhVectors_new() {

	var popupVector = DMPopup.getInstance({
		name: 'popupVector',
		includeCallback: function () {
			this.openPopup('open', '');
		},
		onSuccess: function(data) {
			fhVectors_search();
		}
	});
	
}