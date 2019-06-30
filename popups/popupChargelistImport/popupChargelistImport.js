function popupChargelistImport_refresh() {
	
	jQuery('#popupChargelistImport_chargelists').hide();
	jQuery('#popupChargelistImport_chargelists tbody').html("");
	jQuery('#popupChargelistImport_loading').slideDown();
	
	jQuery.post(
		'index.php?controller=chargelist&type=json&task=jsonGetChargelistFTPlist',
		{},
		function (data) { 
			
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				chargelists = result.chargelists;
				chargelistsCount = chargelists.length;
				
				for (var i = 0; i < chargelistsCount; i++) {
					var chargelist = chargelists[i];
					
					var rowHtml = 
						'<tr>' +
							'<td>' + chargelist + '</td>' +
							'<td style="text-align: center;"><button class="btn btn-mini btn-primary" onclick="popupChargelistImport_import(\'' + chargelist + '\'); return false;">Importa</button>' +
						'</tr>';
				
					jQuery('#popupChargelistImport_chargelists tbody').append(rowHtml);
					
				}
				
				jQuery('#popupChargelistImport_loading').slideUp();
				jQuery('#popupChargelistImport_chargelists').slideDown();
			}
		}
	);
}

function popupChargelistImport_import(chargelistName) {
	
	if (confirm("Sei sicuro di volere importare la lista '" + chargelistName + "'?")) {
	
		jQuery('#popupChargelistImport_chargelists').hide();
		jQuery('#popupChargelistImport_loading').slideDown();
		
		jQuery.post(
			'index.php?controller=chargelist&type=json&task=jsonImportFTPChargelist',
			{
				"name": chargelistName
			},
			function (data) { 
				
				var result = DMResponse.validateJson(data);
				
				if (result != false) {
				
					if (result.result >= 0) {
						alert("Lista importata");
						DMPopup.successPopup('popupChargelistImport');
						jQuery('#popupChargelistImport').modal("hide");
					} else {
						alert("Si è verificato un errore (" + result.result + "): " + result.description);
						jQuery('#popupChargelistImport_loading').slideUp();
						jQuery('#popupChargelistImport_chargelists').slideDown();
					}
				
				}
			}
		);	
		
	}
	
}