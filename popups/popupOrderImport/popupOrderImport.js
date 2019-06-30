function popupOrderImport_refresh() {
	
	jQuery('#popupOrderImport_orders').hide();
	jQuery('#popupOrderImport_orders tbody').html("");
	jQuery('#popupOrderImport_loading').slideDown();
	
	jQuery.post(
		'index.php?controller=order&type=json&task=jsonGetOrderFTPlist',
		{},
		function (data) { 
			
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				orders = result.orders;
				ordersCount = orders.length;
				
				for (var i = 0; i < ordersCount; i++) {
					var order = orders[i];
					
					var rowHtml = 
						'<tr>' +
							'<td>' + order + '</td>' +
							'<td style="text-align: center;"><button class="btn btn-mini btn-primary" onclick="popupOrderImport_import(\'' + order + '\'); return false;">Importa</button>' +
						'</tr>';
				
					jQuery('#popupOrderImport_orders tbody').append(rowHtml);
					
				}
				
				jQuery('#popupOrderImport_loading').slideUp();
				jQuery('#popupOrderImport_orders').slideDown();
			}
		}
	);
}

function popupOrderImport_import(orderName) {
	
	if (confirm("Sei sicuro di volere importare la lista '" + orderName + "'?")) {
	
		jQuery('#popupOrderImport_orders').hide();
		jQuery('#popupOrderImport_loading').slideDown();
		
		jQuery.post(
			'index.php?controller=order&type=json&task=jsonImportFTPOrder',
			{
				"name": orderName
			},
			function (data) { 
				
				var result = DMResponse.validateJson(data);
				
				if (result != false) {
				
					if (result.result >= 0) {
						alert("Lista importata");
						DMPopup.successPopup('popupOrderImport');
						jQuery('#popupOrderImport').modal("hide");
					} else {
						alert("Si è verificato un errore (" + result.result + "): " + result.description);
						jQuery('#popupOrderImport_loading').slideUp();
						jQuery('#popupOrderImport_orders').slideDown();
					}
				
				}
			}
		);	
		
	}
	
}