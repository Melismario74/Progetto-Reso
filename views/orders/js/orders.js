function fhOrders_search(currentPage) {
	
	if (currentPage == undefined) {
		currentPage = 1;
	}
	
	var order_archived = jQuery('#fhOrders_filterOrderArchived').val();
	
	if (order_archived == "Si") {
		order_archived = 1;
	} else if (order_archived == "No"){
		order_archived = 0;
	} else {
		order_archived = -1;
	}
	
	var tbodyElement = '#fhOrders_results_table tbody';
	var tfootElement = '#fhOrders_results_table tfoot td';
	var progressElement = '#fhOrders .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();

	jQuery.post(
		'index.php?controller=order&task=jsonGetOrders&type=json',
		{
			"orderDateFrom": jQuery('#fhOrders_filterOrderDateFrom').val(),
			"orderDateTo": jQuery('#fhOrders_filterOrderDateTo').val(),
			"page": currentPage,
			"order_archived": order_archived,
			"limit": 30
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				totalPages = result.result;
				
				if (totalPages > 0) {
					var orders = result.orders;
					var ordersCount = orders.length;
					
					for (var i = 0; i < ordersCount; i++) {
						var orderData = orders[i];
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center">' + orderData.order_code_str + '</td>' +
								'<td style="text-align: center">' + orderData.order_date_str + '</td>' +
								'<td style="text-align: center"><a href="#" onclick="fhOrders_openOrder(' + orderData.order_id + '); return false;">' + orderData.client_name + '</a></td>' +
								'<td style="text-align: center">' + orderData.notes + '</td>' +
								'<td style="text-align: center">' + orderData.order_archived_str + '</td>' +
							'</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
				}
				
				if (totalPages < 1) {
					totalPages = 1;
				}
				
				var footHtml = "";
				if (currentPage > 1) {
					footHtml += '<a class="previous" href="#" title="Inizio" onclick="fhOrders_search(1); return false;"><i class="icon-arrow-l"></i> << </a>';
					footHtml += '<a class="previous" href="#" title="Precedente" onclick="fhOrders_search(' + (currentPage - 1) + '); return false;"><i class="icon-arrow-l"></i> < </a>';
				}	
				
				footHtml += '<select id="fhOrders_selectionPage" name="fhOrders_selectionPage" class="fhOrders_selectionPage" onchange="fhOrders_search(this.options[this.selectedIndex].value); return false;" >'
				for (var i = 1; i <= totalPages ; i++) { 
					if (i == currentPage) {
					footHtml += '<option style="font-weight: bold;" width="5" selected value="' + i + '" >' + i + '</option>';
					} else {
					footHtml += '<option style="font-weight: bold;" width="5" value="' + i + '" >' + i + '</option>';
					}
				}
				footHtml += '</select>';
				
				if (currentPage < totalPages) {
					footHtml += '<a class="next" href="#" title="Successiva" onclick="fhOrders_search(' + (currentPage + 1) + '); return false;"> > <i class="icon-arrow-r"></i></a>';
					footHtml += '<a class="next" href="#" title="Fine" onclick="fhOrders_search(' + (totalPages) + '); return false;"> >> <i class="icon-arrow-r"></i></a>';
				}
				
				jQuery(tfootElement).html(footHtml);
				
				jQuery(progressElement).slideUp();
				jQuery(tbodyElement).slideDown();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery(progressElement).slideUp();
			}
		}
		
	);
	
}

function fhOrders_openOrder(orderId) {
	
	window.location = 'index.php?controller=order&view=order&orderId=' + orderId;
	
}