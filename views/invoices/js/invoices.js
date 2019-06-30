function fhInvoices_search(currentPage) {
	
	if (currentPage == undefined) {
		currentPage = 1;
	}
	
	var invoice_archived = jQuery('#fhInvoices_filterInvoiceArchived').val();
	
	if (invoice_archived == "Si") {
		invoice_archived = 1;
	} else if (invoice_archived == "No"){
		invoice_archived = 0;
	} else {
		invoice_archived = -1;
	}
	
	var tbodyElement = '#fhInvoices_results_table tbody';
	var tfootElement = '#fhInvoices_results_table tfoot td';
	var progressElement = '#fhInvoices .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();

	jQuery.post(
		'index.php?controller=invoice&task=jsonGetInvoices&type=json',
		{
			"invoiceDateFrom": jQuery('#fhInvoices_filterInvoiceDateFrom').val(),
			"invoiceDateTo": jQuery('#fhInvoices_filterInvoiceDateTo').val(),
			"page": currentPage,
			"invoice_archived": invoice_archived,
			"limit": 30
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				totalPages = result.result;
				
				if (totalPages > 0) {
					var invoices = result.invoices;
					var invoicesCount = invoices.length;
					
					for (var i = 0; i < invoicesCount; i++) {
						var invoiceData = invoices[i];
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center">' + invoiceData.invoice_code_str + '</td>' +
								'<td style="text-align: center">' + invoiceData.invoice_date_str + '</td>' +
								'<td style="text-align: center"><a href="#" onclick="fhInvoices_openInvoice(' + invoiceData.invoice_id + '); return false;">' + invoiceData.client_name + '</a></td>' +
								'<td style="text-align: center">' + invoiceData.notes + '</td>' +
								'<td style="text-align: center">' + invoiceData.invoice_archived_str + '</td>' +
							'</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
				}
				
				if (totalPages < 1) {
					totalPages = 1;
				}
				
				var footHtml = "";
				if (currentPage > 1) {
					footHtml += '<a class="previous" href="#" title="Inizio" onclick="fhInvoices_search(1); return false;"><i class="icon-arrow-l"></i> << </a>';
					footHtml += '<a class="previous" href="#" title="Precedente" onclick="fhInvoices_search(' + (currentPage - 1) + '); return false;"><i class="icon-arrow-l"></i> < </a>';
				}	
				
				footHtml += '<select id="fhInvoices_selectionPage" name="fhInvoices_selectionPage" class="fhInvoices_selectionPage" onchange="fhInvoices_search(this.options[this.selectedIndex].value); return false;" >'
				for (var i = 1; i <= totalPages ; i++) { 
					if (i == currentPage) {
					footHtml += '<option style="font-weight: bold;" width="5" selected value="' + i + '" >' + i + '</option>';
					} else {
					footHtml += '<option style="font-weight: bold;" width="5" value="' + i + '" >' + i + '</option>';
					}
				}
				footHtml += '</select>';
				
				if (currentPage < totalPages) {
					footHtml += '<a class="next" href="#" title="Successiva" onclick="fhInvoices_search(' + (currentPage + 1) + '); return false;"> > <i class="icon-arrow-r"></i></a>';
					footHtml += '<a class="next" href="#" title="Fine" onclick="fhInvoices_search(' + (totalPages) + '); return false;"> >> <i class="icon-arrow-r"></i></a>';
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

function fhInvoices_openInvoice(invoiceId) {
	
	window.location = 'index.php?controller=invoice&view=invoice&invoiceId=' + invoiceId;
	
}