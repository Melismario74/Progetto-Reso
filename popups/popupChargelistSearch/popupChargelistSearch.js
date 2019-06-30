function popupChargelistSearch_search() {
	
	var tbodyElement = '#popupChargelistSearch_results_table tbody';
	var progressElement = '#popupChargelistSearch .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();

	jQuery.post(
		'index.php?controller=chargelist&task=jsonGetChargelists&type=json',
		{
			"archived": 0
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				if (result.result > 0) {
				
					chargelists = result.chargelists;
					chargelistsCount = chargelists.length;
					
					for (var i = 0; i < chargelistsCount; i++) {
						var chargelistData = chargelists[i];
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center">' + chargelistData.chargelist_id + '</td>' +
								'<td style="text-align: center">' +
									chargelistData.chargelist_date_str +
								'</td>' +
								'<td>' +
									'<a href="#" onclick="popupChargelistSearch_selectChargelist(' + chargelistData.chargelist_id + '); return false">' + chargelistData.chargelist_code + '</a>' +
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

function popupChargelistSearch_selectChargelist(chargelistId) {

	DMPopup.successPopup('popupChargelistSearch', chargelistId);
	jQuery('#popupChargelistSearch').modal("hide");

}