var fhLdv_data = {};

function fhLdv_load(ldvId) {

	var tbodyElement = '#fhLdv table tbody';
	var progressElement = '#fhLdv .progress';
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();
	
	jQuery.post(
	    'index.php?controller=ldv&task=jsonLoadLdv&type=json',
	    {
	    	"ldvId": ldvId
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		fhLdv_data.ldv = result.ldv;
	    		
	    		if (fhLdv_data.ldv.rows == undefined) {
		    		fhLdv_data.ldv.rows = Array();
		    	}
	    		
	    		jQuery('#fhLdv_code').html(fhLdv_data.ldv.ldv_code);
	    		jQuery('#fhLdv_date').html(fhLdv_data.ldv.ldv_date_str);
	    		
	    		if (fhLdv_data.ldv.archived == 1) {
	    			jQuery('#fhLdv_labelArchived').show();
	    			jQuery('#fhLdv_btnArchivedToggle').html('Annulla archiviazione');
	    		} else {
	    			jQuery('#fhLdv_labelArchived').hide();
	    			jQuery('#fhLdv_btnArchivedToggle').html('Archivia lista di carico');
	    		}
	    		
	    		for (var i = 0; i < fhLdv_data.ldv.rows.length; i++) {
	    		fhLdv_addRow(fhLdv_data.ldv.rows[i]);
	    		}	    		
	    		jQuery(tbodyElement).slideDown();
	    		
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    	jQuery(progressElement).slideUp();
	    	
	    }
	);
	
}

function fhLdv_addRow(rowData) {

		var rowHtml = 
					    '<tr id="fhLdv_rows_' + rowData.uniqueId + '">' +
					    	'<td style="text-align: center">' + rowData.ddt_code + '</td>' +
					    	'<td style="text-align: left">' + rowData.ddt_date_str + '</td>' +					
					    	'<td><button class="btn btn-mini btn-danger" onclick="fhLdv_deleteRow(\'' + rowData.uniqueId  + '\'); return false;"><i class="icon-white icon-remove"></i></button></td>' +
					    '</tr>';
					
					jQuery('#fhLdv_details tbody').append(rowHtml);
					
}

function fhLdv_archivedToggle() {
	
	if (fhLdv_data.ldv.archived == 1) {
		var newArchived = 0;
	} else {
		var newArchived = 1;
	}
	
	jQuery.post(
	    'index.php?controller=ldv&task=archiveLdv&type=json',
	    {
	    	"ldvId": fhLdv_data.ldv.ldv_id,
	    	"archived": newArchived
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {
	    		alert('Lista di carico aggiornata');
	    		fhLdv_load(fhLdv_data.ldv.ldv_id);
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    }
	 );
	
}

function fhLdv_addDdt() {
	
	var popupDdt = DMPopup.getInstance({
		name: 'popupDdt',
		includeCallback: function () {
			this.openPopup('open', '');
		},
	    onSuccess: function (data) {
	    	fhLdv_addDocument(popupDdt_data.uniqueId , popupDdt_data.ddtCode, popupDdt_data.ddtDateStr);
	    }
	});
	
}


function fhLdv_addDocument(uniqueId, ddtCode, ddtDateStr) {

		var rowHtml = 
					    '<tr id="fhLdv_rows_' + uniqueId + '">' +
					    	'<td style="text-align: center">' + ddtCode + '</td>' +
					    	'<td style="text-align: left">' + ddtDateStr + '</td>' +					
					    	'<td><button class="btn btn-mini btn-danger" onclick="fhLdv_deleteRow(\'' + uniqueId  + '\'); return false;"><i class="icon-white icon-remove"></i></button></td>' +
					    '</tr>';
					
					jQuery('#fhLdv_details tbody').append(rowHtml);
					
}



/* Aggiunta da Mario per il programma di Felsinea*/


function fhLdv_addItem(ddtId) {
	
	var progressElement = '#fhLdv .progress';
	jQuery(progressElement).slideDown();
	
	
	
	jQuery.post(
		'index.php?controller=ddt&task=jsonGetDdt&type=json',
		{
			"ddtId":ddtId
		},
		function (data) {
			
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {
				var rowData = result.ddt;
				fhLdv_data.ldv.rows.push(rowData);
				fhLdv_addRow(rowData);			
				jQuery(progressElement).slideUp();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery(progressElement).slideUp();
			}			
		}
	);	
}

function fhLdv_comeback() {
	
	
	if (!confirm("Sei sicuro di uscire senza salvare?")) {
		return false;
	}
	 
	
	jQuery.post(
	    'index.php?controller=ldv&task=jsonGetArrival&type=json',
	    {
	    	"ldvId": fhLdv_data.ldv.ldv_id
	    },
		  function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
			if ((result != false) && (result.result >= 0)) {
			window.location = 'index.php?controller=arrival&view=arrival&arrivalId='+ result.result;
			}
	    }
	 );
	
}