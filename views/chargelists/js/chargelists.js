function fhChargelists_importChargelist_click() {
	
	var popupChargelistImport = DMPopup.getInstance({
			name: 'popupChargelistImport',
			includeCallback: function () {
				this.openPopup('open', '');
			},
			onSuccess: function(data) {
				fhChargelists_search();
			}
		});
	
}

function fhChargelists_search() {

	var tbodyElement = '#fhChargelists_results_table tbody';
	var progressElement = '#fhChargelists .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();

	jQuery.post(
		'index.php?controller=chargelist&task=jsonGetChargelists&type=json',
		{
			
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
									'<a href="index.php?controller=chargelist&view=chargelist&chargelistId=' + chargelistData.chargelist_id + '">' + chargelistData.chargelist_code + '</a>' +
								'</td>' +
								'<td style="text-align: center">' +
									chargelistData.archived_str +
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