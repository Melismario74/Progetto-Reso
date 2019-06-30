var popupDdt_data = {};


/* Avallo Mario per programma feslinea*/

function popupDdt_newDdt() {
	
	popupDdt_data = {};
	popupDdt_data.ddt = {};
	popupDdt_data.ddtId = -1;
	popupDdt_data.ddt.rows = Array();
	
}



/* Avallo Mario per programma feslinea*/

function popupDdt_editDdt(ddtId) {

	jQuery.post(
	    'index.php?controller=ddt&task=jsonLoadDdt&type=json',
	    {
	    	"ddtId": ddtId
	    },
	    function (data) {	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		popupDdt_data.ddt = result.ddt;	    		
	    			    		
	    		jQuery('#popupDdt_editDdtCode').val(popupDdt_data.ddt.ddt_code_str);
	    		jQuery('#popupDdt_editDdtDate').val(popupDdt_data.ddt.ddt_date_str);
				jQuery('#popupDdt_editCargo').val(popupDdt_data.ddt.cargo);
				jQuery('#popupDdt_editNotes').val(popupDdt_data.ddt.notes);
	    					
	    		
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    }
	);
}

	
/* Avallo Mario per programma feslinea*/

function popupDdt_add() {
	

	var postData = {
		"ddtId": popupDdt_data.ddt.ddt_id,
		"ddt_code": jQuery('#popupDdt_editDdtCode').val(),
		"ddt_date": jQuery('#popupDdt_editDdtDate').val(),
		"cargo":	jQuery('#popupDdt_editCargo').val(),
		"notes":	jQuery('#popupDdt_editNotes').val()
	};
	
	jQuery.post(
	    'index.php?controller=ddt&task=jsonAdd&type=json',
	    postData,
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {
				popoupDdt_data = result.ddt;
				rowData = result.ddt;
				popupDdt_data.uniqueId = result.ddt.uniqueId;
				popupDdt_data.ddtCode = result.ddt.ddt_code;
				popupDdt_data.ddtCodeStr = result.ddt.ddt_code_str;
				popupDdt_data.ddtDateStr = result.ddt.ddt_date_str;
				popupDdt_data.cargo = result.ddt.cargo;
				popupDdt_data.notes = result.ddt.notes;
				DMPopup.successPopup('popupDdt',rowData);
				jQuery('#popupDdt').modal("hide");
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    }
	);
	
}



/* Avallo da Mario per il programma di Felsinea


function popupDdt_addRow(ddtId) {	
	
	jQuery.post(
		'index.php?controller=ddt&task=jsonLoadDdt&type=json',
		{
			"ddtId":ddtId
		},
		function (data) {
			
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {
				popupDdt_data.ddt = result.ddt;				
				
			var rowHtml = 
				'<tr id="popupDdt_rows_' + popupDdt_data.ddt.uniqueId + '">' +
					'<td>' + popupDdt_data.ddt.ddt_code_str + '</td>' +
					'<td>' + popupDdt_data.ddt.ddt_date_str + '</td>' +
					'<td class="popupDdt_editMode"><button class="btn btn-mini btn-danger" onclick="popupDdt_deleteRow(\'' + popupDdt_data.ddt.uniqueId  + '\'); return false;"><i class="icon-white icon-remove"></i></button></td>' +
				'</tr>';
			jQuery('#popupDdt_details table tbody').append(rowHtml);
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}			
		}
	);	
}
 */