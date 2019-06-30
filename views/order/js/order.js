var fhOrder_data = {};

function fhOrder_editMode() {

	jQuery('.fhOrder_viewMode').hide();
	
	jQuery('.fhOrder_viewField').each(function () {
		var editField = jQuery(this).attr('data-view-for');
		jQuery('#' + editField).val(jQuery(this).html());
	})
	
	jQuery('.fhOrder_editMode').show();
	
	fhOrder_data.currentMode = "EDIT";
	
}

function fhOrder_viewMode() {

	jQuery('.fhOrder_editMode').hide();
	jQuery('.fhOrder_viewMode').show();
	if (fhOrder_data.order.order_archived == 1) {
	    jQuery('#fhOrder_labelArchived').show();
	    jQuery('#fhOrder_btnArchivedToggle').html('Annulla archiviazione');
	} else {
		jQuery('#fhOrder_labelArchived').hide();
		jQuery('#fhOrder_btnArchivedToggle').html('Archivia lista di carico');
	}
	
	fhOrder_data.currentMode = "VIEW";
	
}

function fhOrder_newOrder() {
	
	fhOrder_editMode();
	fhOrder_data = {};
	fhOrder_data.orderId = -1;
	fhOrder_data.order = {};
	fhOrder_data.order.rows = Array();
	
}

function fhOrder_editOrder(orderId) {

	var progressElement = '#fhOrder .progress';
	jQuery(progressElement).slideDown();
	
	jQuery.post(
	    'index.php?controller=order&task=jsonLoadOrder&type=json',
	    {
	    	"orderId": orderId
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		fhOrder_data.order = result.order;
	    		fhOrder_data.orderId = result.order.order_id;
	    		if (fhOrder_data.order.rows == undefined) {
		    		fhOrder_data.order.rows = Array();
		    	}
	    		
	    		jQuery('#fhOrder_viewOrderCode').html(fhOrder_data.order.order_code);
	    		jQuery('#fhOrder_viewOrderDate').html(fhOrder_data.order.order_date_str);
	    		jQuery('#fhOrder_viewClientName').html(fhOrder_data.order.client_name);
	    		jQuery('#fhOrder_viewNotes').html(fhOrder_data.order.notes);
	    		jQuery('#fhOrder_viewSubject').html(fhOrder_data.order.subject);

	    		
	    		for (var i = 0; i < fhOrder_data.order.rows.length; i++) {
	    			fhOrder_addRow(fhOrder_data.order.rows[i]);
	    		}
	    		
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    	fhOrder_viewMode();
	    	
	    	jQuery(progressElement).slideUp();
	    	
	    }
	);
	
}

function fhOrder_close() {
	
	if (fhOrder_data.currentMode == "EDIT") {
		if (!confirm("Sei sicuro di uscire senza salvare?")) {
			return false;
		}
	} 
	
	window.location = 'index.php?controller=order';
		
}

function fhOrder_save() {
	
	if (!jQuery('#fhOrder_form').valid()) {
		return false;
	}
	
	var myPostArray = DMUtil.inputsToArray('#fhOrder_form');
	myPostArray.push('orderId=' + fhOrder_data.orderId);
	var rowsData = escape(JSON.stringify(fhOrder_data.order.rows));
	myPostArray.push('rowsData=' + rowsData);
	
	var myPostData = myPostArray.join('&');
	
	jQuery.post(
	    'index.php?controller=order&task=jsonSave&type=json',
	    myPostData,
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		alert("Documento salvato");
	    		window.location = 'index.php?controller=order';
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    }
	);
	
}

function fhOrder_delete() {
	
	if (confirm("L'operazione non è reversibile. Eliminare la lista di prelievo?")) {
		jQuery.post(
		    'index.php?controller=order&task=jsonDelete&type=json',
		    {
		    	"orderId": fhOrder_data.order.order_id
		    },
		    function (data) {
		    	
		    	var result = DMResponse.validateJson(data);
		    	
		    	if ((result != false) && (result.result >= 0)) {	
		    		alert("Documento eliminato");
		    		window.location = 'index.php?controller=order';
		    	} else {
		    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
		    	}
		    	
		    }
		);
	}
	
}

function fhOrder_addRow(rowData) {

	var rowHtml = 
	    '<tr id="fhOrder_rows_' + rowData.uniqueId + '">' +
	    	'<td>' + rowData.udm_code + '</td>' +
			'<td>' + rowData.udm_code_old + '</td>' +
			'<td>' + rowData.article_code + '</td>' +
	    	'<td>' + rowData.description + '</td>' +
	    	'<td>' + rowData.quantity_units + '</td>' +
	    	'<td>' + rowData.ubicazione + '</td>' +
	    	'<td class="fhOrder_editMode"><button class="btn btn-mini btn-danger" onclick="fhOrder_deleteRow(\'' + rowData.uniqueId  + '\'); return false;"><i class="icon-white icon-remove"></i></button></td>' +
	    '</tr>';
	    
	jQuery('#fhOrder_details table tbody').append(rowHtml);

}

/* 
function fhOrder_addItem(rowData) {

	var rowHtml = 
	    '<tr id="fhOrder_rows_' + rowData.uniqueId + '">' +
	    	'<td>' + 'SCARTO' + '</td>' +
			'<td>' + '' + '</td>' +
			'<td>' + rowData.article_code + '</td>' +
	    	'<td>' + rowData.description + '</td>' +
	    	'<td>' + rowData.quantity_units + '</td>' +
	    	'<td>' + 'SCARTO' + '</td>' +
	    	'<td class="fhOrder_editMode"><button class="btn btn-mini btn-danger" onclick="fhOrder_deleteRow(\'' + rowData.uniqueId  + '\'); return false;"><i class="icon-white icon-remove"></i></button></td>' +
	    '</tr>';
	    
	jQuery('#fhOrder_details table tbody').append(rowHtml);

}
 */
function fhOrder_deleteRow(rowId) {

	jQuery('#fhOrder_rows_' + rowId).remove();
	for (var i = 0; i < fhOrder_data.order.rows.length; i++) {
		if (fhOrder_data.order.rows[i].uniqueId == rowId) {
			fhOrder_data.order.rows.splice(i, 1);
		}
	}
	
	
}

function fhOrder_addStock(udmCodeData) {

	

	var progressElement = '#fhOrder .progress';
	jQuery(progressElement).slideDown();
	
	
	if (udmCodeData.length > 0) {	
		jQuery.post(
			'index.php?controller=order&task=jsonGetStockRows&type=json',
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
							
							fhOrder_addRow(rowData);
							fhOrder_data.order.rows.push(rowData);
							
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

/*
function fhOrder_addItems(stockId) {

	

	var progressElement = '#fhOrder .progress';
	jQuery(progressElement).slideDown();
	
	
		jQuery.post(
			'index.php?controller=order&task=jsonGetStockItems&type=json',
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
							
							fhOrder_addItem(rowData);
							fhOrder_data.order.rows.push(rowData);
							
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
*/

function fhOrder_addStockFromUdm() {
	
	var udmCode = jQuery('#fhOrder_editUdm').val();
	
	fhOrder_addStock(1, udmCode);
	
}

function fhOrder_showUdmStatus(stockId) {
	
	var params = 'stockId=' + stockId;

	var popupUdmListOrder = DMPopup.getInstance({
		name: 'popupUdmListOrder',
		includeCallback: function () {
			this.openPopup('open' , params);
		},
	    onSuccess: function (udmCodeData) {
	    	fhOrder_addStock(udmCodeData);
	    }
	});
	
}

function fhOrder_export() {

	jQuery.post(
		'index.php?controller=order&task=jsonExportOrder&type=json',
		{
			"orderId": fhOrder_data.order.order_id
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

function fhOrder_print() {

	jQuery.post(
		'index.php?controller=order&task=jsonPrintOrder&type=json',
		{
			"orderId": fhOrder_data.order.order_id
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

function fhOrder_archivedToggle() {
	
	
	
	if (fhOrder_data.order.order_archived == 1) {
		var newArchived = 0;
	} else {
		var newArchived = 1;
	}
	 jQuery.post(
	    'index.php?controller=order&task=archiveOrder&type=json',
	    {
	    	"orderId": fhOrder_data.order.order_id,
	    	"archived": newArchived
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {
	    		alert('Lista di carico aggiornata');
	    		window.location = 'index.php?controller=order';
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    }
	 );
	
}