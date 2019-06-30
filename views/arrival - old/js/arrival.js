var fhArrival_data = {};

/* Avallo Mario per programma feslinea*/

function fhArrival_editMode() {

	jQuery('.fhArrival_viewMode').hide();
	
	jQuery('.fhArrival_viewField').each(function () {
		var editField = jQuery(this).attr('data-view-for');
		jQuery('#' + editField).val(jQuery(this).html());
	})
	
	jQuery('.fhArrival_editMode').show();
	
	fhArrival_data.currentMode = "EDIT";
	
}

/* Avallo Mario per programma feslinea*/

function fhArrival_viewMode() {

	jQuery('.fhArrival_editMode').hide();
	jQuery('.fhArrival_viewMode').show();
	if (fhArrival_data.arrival.arrival_archived == 1) {
	    jQuery('#fhArrival_labelArchived').show();
	    jQuery('#fhArrival_btnArchivedToggle').html('Annulla archiviazione');
	} else {
		jQuery('#fhArrival_labelArchived').hide();
		jQuery('#fhArrival_btnArchivedToggle').html('Archivia lista di carico');
	}
	
	fhArrival_data.currentMode = "VIEW";
	
}

/* Avallo Mario per programma feslinea*/

function fhArrival_newArrival() {
	
	fhArrival_editMode();
	fhArrival_data = {};
	fhArrival_data.arrivalId = -1;
	fhArrival_data.arrival = {};
	fhArrival_data.arrival.rows = Array();
	
}

/* Avallo Mario per programma feslinea*/

function fhArrival_editArrival(arrivalId) {

	var progressElement = '#fhArrival .progress';
	jQuery(progressElement).slideDown();
	
	jQuery.post(
	    'index.php?controller=arrival&task=jsonLoadArrival&type=json',
	    {
	    	"arrivalId": arrivalId
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		fhArrival_data.arrival = result.arrival;
	    		fhArrival_data.arrivalId = result.arrival.arrival_id;
	    		if (fhArrival_data.arrival.rows == undefined) {
		    		fhArrival_data.arrival.rows = Array();
		    	}
	    		
	    		jQuery('#fhArrival_viewArrivalCode').html(fhArrival_data.arrival.arrival_code);
	    		jQuery('#fhArrival_viewArrivalDate').html(fhArrival_data.arrival.arrival_date_str);
	    		jQuery('#fhArrival_viewVectorName').html(fhArrival_data.arrival.vector_name);
	    		jQuery('#fhArrival_viewNotes').html(fhArrival_data.arrival.notes);
	    		jQuery('#fhArrival_viewSubject').html(fhArrival_data.arrival.subject);

	    		
	    		for (var i = 0; i < fhArrival_data.arrival.rows.length; i++) {
	    			fhArrival_addRow(fhArrival_data.arrival.rows[i]);
	    		}
	    		
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    	fhArrival_viewMode();
	    	
	    	jQuery(progressElement).slideUp();
	    	
	    }
	);
	
}

/* Avallo Mario per programma feslinea*/

function fhArrival_close() {
	
	if (fhArrival_data.currentMode == "EDIT") {
		if (!confirm("Sei sicuro di uscire senza salvare?")) {
			return false;
		}
	} 
	
	window.location = 'index.php?controller=arrival';
		
}

/* Avallo Mario per programma feslinea*/

function fhArrival_save() {
	
	if (!jQuery('#fhArrival_form').valid()) {
		return false;
	}
	
	var myPostArray = DMUtil.inputsToArray('#fhArrival_form');
	myPostArray.push('arrivalId=' + fhArrival_data.arrivalId);
	var rowsData = escape(JSON.stringify(fhArrival_data.arrival.rows));
	myPostArray.push('rowsData=' + rowsData);
	
	var myPostData = myPostArray.join('&');
	
	jQuery.post(
	    'index.php?controller=arrival&task=jsonSave&type=json',
	    myPostData,
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		alert("Documento salvato");
	    		window.location = 'index.php?controller=arrival';
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    }
	);
	
}

/* Avallo Mario per programma feslinea*/

function fhArrival_delete() {
	
	if (confirm("L'operazione non è reversibile. Eliminare il DDT?")) {
		jQuery.post(
		    'index.php?controller=arrival&task=jsonDelete&type=json',
		    {
		    	"arrivalId": fhArrival_data.arrival.arrival_id
		    },
		    function (data) {
		    	
		    	var result = DMResponse.validateJson(data);
		    	
		    	if ((result != false) && (result.result >= 0)) {	
		    		alert("Documento eliminato");
		    		window.location = 'index.php?controller=arrival';
		    	} else {
		    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
		    	}
		    	
		    }
		);
	}
	
}
/*
function fhArrival_deleteRow(rowId) {

	if (fhArrival_data.arrival.arrival_id > 0) {
		if (!confirm("Cancellare una riga non ripristinerà le quantità nelle UDM. Sicuro di continuare?")) {
			return false;
		}
	}
	
	jQuery('#fhArrival_rows_' + rowId).remove();
	for (var i = 0; i < fhArrival_data.arrival.rows.length; i++) {
		if (fhArrival_data.arrival.rows[i].uniqueId == rowId) {
			fhArrival_data.arrival.rows.splice(i, 1);
		}
	}
	
	
}
*/


/* Aggiunta da Mario per il programma di Felsinea*/


function fhArrival_addLdv() {
	
	var popupLdv = DMPopup.getInstance({
		name: 'popupLdv',
		includeCallback: function () {
			this.openPopup('open', '');
		},
	    onSuccess: function (data) {
	    	fhArrival_addRow(popupLdv_data.ldvId);
	    }
	});
	
}


/* Aggiunta da Mario per il programma di Felsinea*/


function fhArrival_addRow(ldvId) {
	
	var progressElement = '#fhArrival .progress';
	jQuery(progressElement).slideDown();
	
	
	
	jQuery.post(
		'index.php?controller=ldv&task=jsonLoadLdv&type=json',
		{
			"ldvId":ldvId
		},
		function (data) {
			
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {
				fhArrival_data.arrival = result.ldv;
				
				/*if (result.rows != undefined) {
					var rowsCount = result.rows.length;
				} else {
					var rowsCount = 0;
				}
				
				if (rowsCount > 0) {
					for (var i = 0; i < rowsCount; i++) {
						var rowData = result.rows[i];
						
						fhArrival_addRow(rowData);
						fhArrival_data.arrival.rows.push(rowData);
						
					}
				}
				*/
				
				
			var rowHtml = 
				'<tr id="fhArrival_rows_' + fhArrival_data.arrival.uniqueId + '">' +
					'<td>' + fhArrival_data.arrival.ldv_code_str + '</td>' +
					'<td>' + fhArrival_data.arrival.ldv_date_str + '</td>' +
					'<td>' + fhArrival_data.arrival.sender + '</td>' +
					'<td>' + fhArrival_data.arrival.pallet + '</td>' +
					'<td>' + fhArrival_data.arrival.carton + '</td>' +
					'<td>' + fhArrival_data.arrival.notes + '</td>' +
					'<td class="fhArrival_editMode"><button class="btn btn-mini btn-danger" onclick="fhArrival_deleteRow(\'' + fhArrival_data.arrival.uniqueId  + '\'); return false;"><i class="icon-white icon-remove"></i></button></td>' +
				'</tr>';
			jQuery('#fhArrival_details table tbody').append(rowHtml);
			jQuery(progressElement).slideUp();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery(progressElement).slideUp();
			}			
		}
	);	
}

/* 

function fhArrival_addLdvCode(LdvCodeData) {

	

	var progressElement = '#fhArrival .progress';
	jQuery(progressElement).slideDown();
	
	
	if (udmCodeData.length > 0) {	
		jQuery.post(
			'index.php?controller=arrival&task=jsonGetStockRows&type=json',
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
							
							fhArrival_addRow(rowData);
							fhArrival_data.arrival.rows.push(rowData);
							
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
*/

/*
function fhArrival_export() {

	jQuery.post(
		'index.php?controller=arrival&task=jsonExportArrival&type=json',
		{
			"arrivalId": fhArrival_data.arrival.arrival_id
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



function fhArrival_print() {

	jQuery.post(
		'index.php?controller=arrival&task=jsonPrintArrival&type=json',
		{
			"arrivalId": fhArrival_data.arrival.arrival_id
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

function fhArrival_archivedToggle() {
	
	
	
	if (fhArrival_data.arrival.arrival_archived == 1) {
		var newArchived = 0;
	} else {
		var newArchived = 1;
	}
	 jQuery.post(
	    'index.php?controller=arrival&task=archiveArrival&type=json',
	    {
	    	"arrivalId": fhArrival_data.arrival.arrival_id,
	    	"archived": newArchived
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {
	    		alert('Lista di carico aggiornata');
	    		window.location = 'index.php?controller=arrival';
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    }
	 );
	
}
*/