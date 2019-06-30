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
	    			    		
	    		jQuery('#popupDdt_editDdtCode').val(popupDdt_data.ddt.ddt_code);
	    		jQuery('#popupDdt_editDdtDate').val(popupDdt_data.ddt.ddt_date_str);
	    					
	    		
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    }
	);
}

	
/* Avallo Mario per programma feslinea*/

function popupDdt_save() {
	

	var postData = {
		"ddtId": popupDdt_data.ddt.ddt_id,
		"ddt_code": jQuery('#popupDdt_editDdtCode').val(),
		"ddt_date": jQuery('#popupDdt_editDdtDate').val()			
	};
	
	jQuery.post(
	    'index.php?controller=ddt&task=jsonSave&type=json',
	    postData,
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {
				popoupDdt_data = result.ddt;
				popupDdt_data.ddtId = result.ddt.ddt_id;
				DMPopup.successPopup('popupDdt', popupDdt_data.ddtId);
				jQuery('#popupDdt').modal("hide");
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    }
	);
	
}

/*Avallo Mario per programma feslinea*/

function popupDdt_addDdt() {
	
	var popupMovement = DMPopup.getInstance({
		name: 'popupDdt',
		includeCallback: function () {
			this.openPopup('open', '');
		},
		onSuccess: function (data) {
			popupDdt_addRow(popupDdt_data.ddtId);
		}
	});
}



/* Avallo da Mario per il programma di Felsinea*/


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
