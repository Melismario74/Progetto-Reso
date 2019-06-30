var popupAddLdv_data = {};


/* Avallo Mario per programma feslinea*/

function popupAddLdv_newLdv() {
	
	popupAddLdv_data = {};
	popupAddLdv_data.ldv = {};
	popupAddLdv_data.ldvId = -1;
	popupAddLdv_data.ldv.ddts = Array();
	
}

/* Avallo Mario per programma feslinea*/

function popupAddLdv_editLdv(ldvId) {

	jQuery.post(
	    'index.php?controller=ldv&task=jsonLoadLdv&type=json',
	    {
	    	"ldvId": ldvId
	    },
	    function (data) {	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		popupAddLdv_data.ldv = result.ldv;	    		
	    		if (popupAddLdv_data.ldv.ddts == undefined) {
		    		popupAddLdv_data.ldv.ddts = Array();
		    	}
	    		
	    		jQuery('#popupAddLdv_editLdvCode').val(popupAddLdv_data.ldv.ldv_code);
	    		jQuery('#popupAddLdv_editLdvDate').val(popupAddLdv_data.ldv.ldv_date_str);
	    		jQuery('#popupAddLdv_editLdvSender').val(popupAddLdv_data.ldv.sender);
	    		jQuery('#popupAddLdv_editLdvNotes').val(popupAddLdv_data.ldv.notes);
	    		jQuery('#popupAddLdv_editLdvCarton').val(popupAddLdv_data.ldv.carton);
				jQuery('#popupAddLdv_editLdvPallet').val(popupAddLdv_data.ldv.pallet);
					
	    		
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    }
	);
}

	
/* Avallo Mario per programma feslinea*/

function popupAddLdv_add() {
	

	var postData = {
		"ldvId": popupAddLdv_data.ldv.ldv_id,
		"ldv_code": jQuery('#popupAddLdv_editLdvCode').val(),
		"ldv_date": jQuery('#popupAddLdv_editLdvDate').val(),
		"sender": jQuery('#popupAddLdv_editLdvSender').val(),
		"notes": jQuery('#popupAddLdv_editLdvNotes').val(),
		"carton": jQuery('#popupAddLdv_editLdvCarton').val(),
		"pallet": jQuery('#popupAddLdv_editLdvPallet').val()
	};

	jQuery.post(
	    'index.php?controller=ldv&task=jsonAdd&type=json',
	    postData,
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		rowData = result.ldv;
				popupAddLdv_data.ldvId = result.ldv.ldv_id;
				popupAddLdv_data.uniqueId = result.ldv.uniqueId;
				popupAddLdv_data.ldvCodeStr = result.ldv.ldv_code_str;
				popupAddLdv_data.ldvDateStr = result.ldv.ldv_date_str;
				popupAddLdv_data.sender = result.ldv.sender;
				popupAddLdv_data.pallet = result.ldv.pallet;
				popupAddLdv_data.carton = result.ldv.carton;
				popupAddLdv_data.notes = result.ldv.notes;
				DMPopup.successPopup('popupAddLdv', rowData);
				jQuery('#popupAddLdv').modal("hide");
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    }
	);
	
}
