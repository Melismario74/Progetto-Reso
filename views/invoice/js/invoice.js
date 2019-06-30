var fhInvoice_data = {};

function fhInvoice_editMode() {

	jQuery('.fhInvoice_viewMode').hide();
	
	jQuery('.fhInvoice_viewField').each(function () {
		var editField = jQuery(this).attr('data-view-for');
		jQuery('#' + editField).val(jQuery(this).html());
	})
	
	jQuery('.fhInvoice_editMode').show();
	
	fhInvoice_data.currentMode = "EDIT";
	
}

function fhInvoice_viewMode() {

	jQuery('.fhInvoice_editMode').hide();
	jQuery('.fhInvoice_viewMode').show();
	if (fhInvoice_data.invoice.invoice_archived == 1) {
	    jQuery('#fhInvoice_labelArchived').show();
	    jQuery('#fhInvoice_btnArchivedToggle').html('Annulla archiviazione');
	} else {
		jQuery('#fhInvoice_labelArchived').hide();
		jQuery('#fhInvoice_btnArchivedToggle').html('Archivia lista di carico');
	}
	
	fhInvoice_data.currentMode = "VIEW";
	
}

function fhInvoice_newInvoice() {
	
	fhInvoice_editMode();
	fhInvoice_data = {};
	fhInvoice_data.invoiceId = -1;
	fhInvoice_data.invoice = {};
	fhInvoice_data.invoice.rows = Array();
	
}

function fhInvoice_editInvoice(invoiceId) {

	var progressElement = '#fhInvoice .progress';
	jQuery(progressElement).slideDown();
	
	jQuery.post(
	    'index.php?controller=invoice&task=jsonLoadInvoice&type=json',
	    {
	    	"invoiceId": invoiceId
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		fhInvoice_data.invoice = result.invoice;
	    		fhInvoice_data.invoiceId = result.invoice.invoice_id;
	    		if (fhInvoice_data.invoice.rows == undefined) {
		    		fhInvoice_data.invoice.rows = Array();
		    	}
	    		
	    		jQuery('#fhInvoice_viewInvoiceCode').html(fhInvoice_data.invoice.invoice_code);
	    		jQuery('#fhInvoice_viewInvoiceDate').html(fhInvoice_data.invoice.invoice_date_str);
	    		jQuery('#fhInvoice_viewClientName').html(fhInvoice_data.invoice.client_name);
	    		jQuery('#fhInvoice_viewNotes').html(fhInvoice_data.invoice.notes);
	    		jQuery('#fhInvoice_viewSubject').html(fhInvoice_data.invoice.subject);

	    		
	    		for (var i = 0; i < fhInvoice_data.invoice.rows.length; i++) {
	    			fhInvoice_addRow(fhInvoice_data.invoice.rows[i]);
	    		}
	    		
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    	fhInvoice_viewMode();
	    	
	    	jQuery(progressElement).slideUp();
	    	
	    }
	);
	
}

function fhInvoice_close() {
	
	if (fhInvoice_data.currentMode == "EDIT") {
		if (!confirm("Sei sicuro di uscire senza salvare?")) {
			return false;
		}
	} 
	
	window.location = 'index.php?controller=invoice';
		
}

function fhInvoice_save() {
	
	if (!jQuery('#fhInvoice_form').valid()) {
		return false;
	}
	
	var myPostArray = DMUtil.inputsToArray('#fhInvoice_form');
	myPostArray.push('invoiceId=' + fhInvoice_data.invoiceId);
	var rowsData = escape(JSON.stringify(fhInvoice_data.invoice.rows));
	myPostArray.push('rowsData=' + rowsData);
	
	var myPostData = myPostArray.join('&');
	
	jQuery.post(
	    'index.php?controller=invoice&task=jsonSave&type=json',
	    myPostData,
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		alert("Documento salvato");
	    		window.location = 'index.php?controller=invoice';
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    }
	);
	
}

function fhInvoice_delete() {
	
	if (confirm("L'operazione non è reversibile. Eliminare il DDT?")) {
		jQuery.post(
		    'index.php?controller=invoice&task=jsonDelete&type=json',
		    {
		    	"invoiceId": fhInvoice_data.invoice.invoice_id
		    },
		    function (data) {
		    	
		    	var result = DMResponse.validateJson(data);
		    	
		    	if ((result != false) && (result.result >= 0)) {	
		    		alert("Documento eliminato");
		    		window.location = 'index.php?controller=invoice';
		    	} else {
		    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
		    	}
		    	
		    }
		);
	}
	
}

function fhInvoice_addRow(rowData) {

	var rowHtml = 
	    '<tr id="fhInvoice_rows_' + rowData.uniqueId + '">' +
	    	'<td>' + rowData.udm_code + '</td>' +
			'<td>' + rowData.udm_code_old + '</td>' +
			'<td>' + rowData.article_code + '</td>' +
	    	'<td>' + rowData.description + '</td>' +
	    	'<td>' + rowData.quantity_units + '</td>' +
	    	'<td>' + rowData.ubicazione + '</td>' +
	    	'<td class="fhInvoice_editMode"><button class="btn btn-mini btn-danger" onclick="fhInvoice_deleteRow(\'' + rowData.uniqueId  + '\'); return false;"><i class="icon-white icon-remove"></i></button></td>' +
	    '</tr>';
	    
	jQuery('#fhInvoice_details table tbody').append(rowHtml);

}


function fhInvoice_addItem(rowData) {

	var rowHtml = 
	    '<tr id="fhInvoice_rows_' + rowData.uniqueId + '">' +
	    	'<td>' + 'SCARTO' + '</td>' +
			'<td>' + '' + '</td>' +
			'<td>' + rowData.article_code + '</td>' +
	    	'<td>' + rowData.description + '</td>' +
	    	'<td>' + rowData.quantity_units + '</td>' +
	    	'<td>' + 'SCARTO' + '</td>' +
	    	'<td class="fhInvoice_editMode"><button class="btn btn-mini btn-danger" onclick="fhInvoice_deleteRow(\'' + rowData.uniqueId  + '\'); return false;"><i class="icon-white icon-remove"></i></button></td>' +
	    '</tr>';
	    
	jQuery('#fhInvoice_details table tbody').append(rowHtml);

}

function fhInvoice_deleteRow(rowId) {

	if (fhInvoice_data.invoice.invoice_id > 0) {
		if (!confirm("Cancellare una riga non ripristinerà le quantità nelle UDM. Sicuro di continuare?")) {
			return false;
		}
	}
	
	jQuery('#fhInvoice_rows_' + rowId).remove();
	for (var i = 0; i < fhInvoice_data.invoice.rows.length; i++) {
		if (fhInvoice_data.invoice.rows[i].uniqueId == rowId) {
			fhInvoice_data.invoice.rows.splice(i, 1);
		}
	}
	
	
}

function fhInvoice_addStock(udmCodeData) {

	

	var progressElement = '#fhInvoice .progress';
	jQuery(progressElement).slideDown();
	
	
	if (udmCodeData.length > 0) {	
		jQuery.post(
			'index.php?controller=invoice&task=jsonGetStockRows&type=json',
			{
				"udmCodeData": JSON.stringify(udmCodeData)
			},
			function (data) {
				
				var result = DMResponse.validateJson(data);
				
				if ((result != false) && (result.result >= 0)) {
					
					if (result.rows != undefined) {
						var rowsCount = result.rows.length;
					} else {
						var rowsCount = 0;
					}
					
					if (rowsCount > 0) {
						for (var i = 0; i < rowsCount; i++) {
							var rowData = result.rows[i];
							
							fhInvoice_addRow(rowData);
							fhInvoice_data.invoice.rows.push(rowData);
							
						}
					}
					
					alert('Caricate ' + rowsCount + ' righe');
					
					jQuery(progressElement).slideUp();
					
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
					jQuery(progressElement).slideUp();
				}
				
			}
		);
	}
	
}


function fhInvoice_addItems(stockId) {

	

	var progressElement = '#fhInvoice .progress';
	jQuery(progressElement).slideDown();
	
	
		jQuery.post(
			'index.php?controller=invoice&task=jsonGetStockItems&type=json',
			{
				"stockId": stockId
			},
			function (data) {
				
				var result = DMResponse.validateJson(data);
				
				if ((result != false) && (result.result >= 0)) {
					
					if (result.rows != undefined) {
						var rowsCount = result.rows.length;
					} else {
						var rowsCount = 0;
					}
					
					if (rowsCount > 0) {
						for (var i = 0; i < rowsCount; i++) {
							var rowData = result.rows[i];
							
							fhInvoice_addItem(rowData);
							fhInvoice_data.invoice.rows.push(rowData);
							
						}
					}
					
					alert('Caricate ' + rowsCount + ' righe');
					
					jQuery(progressElement).slideUp();
					
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
					jQuery(progressElement).slideUp();
				}
				
			}
		);
	
	
}

function fhInvoice_addStockFromUdm() {
	
	var udmCode = jQuery('#fhInvoice_editUdm').val();
	
	fhInvoice_addStock(1, udmCode);
	
}

function fhInvoice_showUdmStatus(stockId) {
	
	var params = 'stockId=' + stockId;

	var popupUdmListInvoice = DMPopup.getInstance({
		name: 'popupUdmListInvoice',
		includeCallback: function () {
			this.openPopup('open' , params);
		},
	    onSuccess: function (udmCodeData) {
	    	fhInvoice_addStock(udmCodeData);
	    }
	});
	
}

function fhInvoice_export() {

	jQuery.post(
		'index.php?controller=invoice&task=jsonExportInvoice&type=json',
		{
			"invoiceId": fhInvoice_data.invoice.invoice_id
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
				
				window.open(result.data.export_url, '_blank');
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);

}

function fhInvoice_print() {

	jQuery.post(
		'index.php?controller=invoice&task=jsonPrintInvoice&type=json',
		{
			"invoiceId": fhInvoice_data.invoice.invoice_id
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
				
				window.open(result.data.print_url, '_blank');
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);

}

function fhInvoice_archivedToggle() {
	
	
	
	if (fhInvoice_data.invoice.invoice_archived == 1) {
		var newArchived = 0;
	} else {
		var newArchived = 1;
	}
	 jQuery.post(
	    'index.php?controller=invoice&task=archiveInvoice&type=json',
	    {
	    	"invoiceId": fhInvoice_data.invoice.invoice_id,
	    	"archived": newArchived
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {
	    		alert('Lista di carico aggiornata');
	    		window.location = 'index.php?controller=invoice';
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    }
	 );
	
}