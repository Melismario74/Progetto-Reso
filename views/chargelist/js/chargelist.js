var fhChargelist_data = {};

function fhChargelist_load(chargelistId) {

	var tbodyElement = '#fhChargelist table tbody';
	var progressElement = '#fhChargelist .progress';
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();
	
	jQuery.post(
	    'index.php?controller=chargelist&task=jsonLoadChargelist&type=json',
	    {
	    	"chargelistId": chargelistId,
	    	"getRows": 1
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		fhChargelist_data.chargelist = result.chargelist;
	    		
	    		if (fhChargelist_data.chargelist.rows == undefined) {
		    		fhChargelist_data.chargelist.rows = Array();
		    	}
	    		
	    		jQuery('#fhChargelist_code').html(fhChargelist_data.chargelist.chargelist_code);
	    		jQuery('#fhChargelist_date').html(fhChargelist_data.chargelist.chargelist_date_str);
	    		
	    		if (fhChargelist_data.chargelist.archived == 1) {
	    			jQuery('#fhChargelist_labelArchived').show();
	    			jQuery('#fhChargelist_btnArchivedToggle').html('Annulla archiviazione');
	    		} else {
	    			jQuery('#fhChargelist_labelArchived').hide();
	    			jQuery('#fhChargelist_btnArchivedToggle').html('Archivia lista di carico');
	    		}
	    		
	    		for (var i = 0; i < fhChargelist_data.chargelist.rows.length; i++) {
	    			var chargelistRow = fhChargelist_data.chargelist.rows[i];
						
					var rowHtml = 
					    '<tr>' +
					    	'<td style="text-align: center">' + chargelistRow.article_code + '</td>' +
					    	'<td style="text-align: left">' + chargelistRow.article_name + '</td>' +
					    	'<td style="text-align: center">' + chargelistRow.quantity + '</td>' +
					    	'<td style="text-align: center">' + chargelistRow.quantity_ok + '</td>' +
					    	'<td style="text-align: center">' + chargelistRow.quantity_stock + '</td>' +
					    	'<td style="text-align: center">' + chargelistRow.quantity_waste + '</td>' +					    	
					    '</tr>';
					
					jQuery(tbodyElement).append(rowHtml);
	    		}
	    		
	    		jQuery(tbodyElement).slideDown();
	    		
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    	jQuery(progressElement).slideUp();
	    	
	    }
	);
	
}

function fhChargelist_archivedToggle() {
	
	if (fhChargelist_data.chargelist.archived == 1) {
		var newArchived = 0;
	} else {
		var newArchived = 1;
	}
	
	jQuery.post(
	    'index.php?controller=chargelist&task=archiveChargelist&type=json',
	    {
	    	"chargelistId": fhChargelist_data.chargelist.chargelist_id,
	    	"archived": newArchived
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {
	    		alert('Lista di carico aggiornata');
	    		fhChargelist_load(fhChargelist_data.chargelist.chargelist_id);
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    }
	 );
	
}