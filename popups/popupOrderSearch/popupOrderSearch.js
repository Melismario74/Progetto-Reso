function popupOrderSearch_search() {
	
	var tbodyElement = '#popupOrderSearch_results_table tbody';
	var progressElement = '#popupOrderSearch .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();

	jQuery.post(
		'index.php?controller=order&task=jsonGetOrders&type=json',
		{
			"archived": 0
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				if (result.result > 0) {
				
					orders = result.orders;
					ordersCount = orders.length;
					
					for (var i = 0; i < ordersCount; i++) {
						var orderData = orders[i];
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center">' + orderData.order_id + '</td>' +
								'<td style="text-align: center">' +
									orderData.order_date_str +
								'</td>' +
								'<td>' +
									'<a href="#" onclick="popupOrderSearch_selectOrder(' + orderData.order_id + '); return false">' + orderData.order_code + '</a>' +
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

function popupOrderSearch_selectOrder(orderId) {

	DMPopup.successPopup('popupOrderSearch', orderId);
	jQuery('#popupOrderSearch').modal("hide");

}