var popupLdv_data = {};



/* Avallo Mario per programma feslinea*/


function popupLdv_load(ldvId) {

	var tbodyElement = '#popupLdv table tbody';
	jQuery(tbodyElement).html("").hide();
	
	
	jQuery.post(
	    'index.php?controller=ldv&task=jsonLoadLdv&type=json',
	    {
	    	"ldvId": ldvId
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		popupLdv_data.ldv = result.ldv;
				popupLdv_data.ldvId = result.ldv.ldv_id;
					if (popupLdv_data.ldv.rows == undefined) {
						popupLdv_data.ldv.rows = Array();
					}
	    		
	    		jQuery('#popupLdv_code').html(popupLdv_data.ldv.ldv_code);
	    		jQuery('#popupLdv_date').html(popupLdv_data.ldv.ldv_date_str);
	    		
					if (popupLdv_data.ldv.archived == 1) {
						jQuery('#popupLdv_labelArchived').show();
						jQuery('#popupLdv_btnArchivedToggle').html('Annulla archiviazione');
					} else {
						jQuery('#popupLdv_labelArchived').hide();
						jQuery('#popupLdv_btnArchivedToggle').html('Archivia lista di carico');
					}
	    		
						for (var i = 0; i < popupLdv_data.ldv.rows.length; i++) {
							var rowData = popupLdv_data.ldv.rows[i];
						
							var rowHtml = 
								'<tr id="popupLdv_rows_' + rowData.uniqueId + '">' +
									'<td style="text-align: center">' + rowData.ddt_code_str + '</td>' +
									'<td style="text-align: center">' + rowData.ddt_date_str + '</td>' +		
									'<td style="text-align: center">' + rowData.cargo + '</td>' +	
									'<td style="text-align: center">' + rowData.notes + '</td>' +	
									'<td><button class="btn btn-mini btn-danger" onclick="popupLdv_deleteRow(\'' + rowData.uniqueId  + '\'); return false;"><i class="icon-white icon-remove"></i></button></td>' +
									'<td><button class="btn btn-mini btn-success" onclick="popupLdv_deleteRow(\'' + rowData.uniqueId  + '\'); return false;"><i class="icon-white icon-camera"></i></button></td>' +
								'</tr>';
							
							jQuery('#popupLdv_table_ddt tbody').append(rowHtml);
						
						}    		
	    		jQuery(tbodyElement).slideDown();
	    		
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    }
	);
	
}


/* Avallo Mario per programma feslinea*/

function popupLdv_addRow(rowData) {

		var rowHtml = 
					    '<tr id="popupLdv_rows_' + rowData.uniqueId + '">' +
					    	'<td style="text-align: center">' + rowData.ddt_code_str + '</td>' +
					    	'<td style="text-align: center">' + rowData.ddt_date_str + '</td>' +		
							'<td style="text-align: center">' + rowData.cargo + '</td>' +	
							'<td style="text-align: center">' + rowData.notes + '</td>' +	
					    	'<td><button class="btn btn-mini btn-danger" onclick="popupLdv_deleteRow(\'' + rowData.uniqueId  + '\'); return false;"><i class="icon-white icon-remove"></i></button></td>' +
							'<td><button class="btn btn-mini btn-success" onclick="popupLdv_deleteRow(\'' + rowData.uniqueId  + '\'); return false;"><i class="icon-white icon-camera"></i></button></td>' +
						'</tr>';
					
					jQuery('#popupLdv_table_ddt tbody').append(rowHtml);
					
}



/* Avallo Mario per programma feslinea*/

function popupLdv_save() {
	
	var myPostArray = DMUtil.inputsToArray('#popupLdv_table_ddt');
	myPostArray.push('ldvId=' + popupLdv_data.ldvId);
	var rowsData = escape(JSON.stringify(popupLdv_data.ldv.rows));
	myPostArray.push('rowsData=' + rowsData);
	
	var myPostData = myPostArray.join('&');
	
	jQuery.post(
	    'index.php?controller=ldv&task=jsonSave&type=json',
	    myPostData,
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		alert("Documento salvato");
				jQuery('#popupLdv').modal("hide") ;
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    }
	);
		
	
}



/*Avallo Mario per programma feslinea*/

function popupLdv_archivedToggle() {
	
	if (popupLdv_data.ldv.archived == 1) {
		var newArchived = 0;
	} else {
		var newArchived = 1;
	}
	
	jQuery.post(
	    'index.php?controller=ldv&task=archiveLdv&type=json',
	    {
	    	"ldvId": popupLdv_data.ldv.ldv_id,
	    	"archived": newArchived
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {
	    		alert('Lista di carico aggiornata');
	    		popupLdv_load(popupLdv_data.ldv.ldv_id);
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    }
	 );
	
}

/*Avallo Mario per programma feslinea*/

function popupLdv_addDdt() {
	
	var popupDdt = DMPopup.getInstance({
		name: 'popupDdt',
		includeCallback: function () {
			this.openPopup('open', '');
		},
	    onSuccess: function (data) {
	    	popupLdv_addDocument(rowData);
			popupLdv_data.ldv.rows.push(rowData);
	    }
	});
	
}


/*Avallo Mario per programma feslinea*/

function popupLdv_addDocument(rowData) {

		var rowHtml = 
					    '<tr id="popupLdv_rows_' + rowData.uniqueId + '">' +
					    	'<td style="text-align: center">' + rowData.ddt_code_str + '</td>' +
					    	'<td style="text-align: center">' + rowData.ddt_date_str + '</td>' +
							'<td style="text-align: center">' + rowData.cargo + '</td>' +	
							'<td style="text-align: center">' + rowData.notes + '</td>' +	
					    	'<td><button class="btn btn-mini btn-danger" onclick="popupLdv_deleteRow(\'' + rowData.uniqueId  + '\'); return false;"><i class="icon-white icon-remove"></i></button></td>' +
							'<td><button class="btn btn-mini btn-success" onclick="popupLdv_deleteRow(\'' + rowData.uniqueId  + '\'); return false;"><i class="icon-white icon-camera"></i></button></td>' +
						'</tr>';
					
					jQuery('#popupLdv_table_ddt tbody').append(rowHtml);
					
}
		

/*Avallo Mario per programma feslinea*/


function popupLdv_addItem(ddtId) {
	
	
	jQuery.post(
		'index.php?controller=ddt&task=jsonGetDdt&type=json',
		{
			"ddtId":ddtId
		},
		function (data) {
			
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {
				var rowData = result.ddt;
				popupLdv_data.ldv.rows.push(rowData);
				popupLdv_addRow(rowData);			
				
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				
			}			
		}
	);	
}
