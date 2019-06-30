var udm_data = {};

function fhUdm_search(currentPage) {
	
	if (currentPage == undefined) {
		currentPage = 1;
	}
	
	
	var tbodyElement = '#fhUdms_table tbody';
	var tfootElement = '#fhUdms_table tfoot td';
	var progressElement = '#fhUdm .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();
	
	jQuery.post(
		'index.php?controller=udm&task=jsonGetUdms&type=json',
		{
			"articleCode": jQuery('#fhUdm_filterArticleCode').val(),
			"udmCode": jQuery('#fhUdm_filterUdmCode').val(),
			"ubicazione": jQuery('#fhUdm_filterUbicazione').val(),
			"getArticleData": 1,
			"getUdmDetails": 1,
			"page": currentPage,
			"limit": 30
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
			
				totalPages = result.result;
				
				if (totalPages > 0) {						
					var udms = result.udms;
					var udmsCount = udms.length;
					
                    //var lastUdmCode = '';
					
					for (var i = 0; i < udmsCount; i++) {
						var udmData = udms[i];

                        						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center"><input type="checkbox" id="fhUdm_checkbox" value="' + udmData.udm_code + '" class="fhUdm_checkbox"/></td>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.udm_code + '</td>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.article_code + '</td>' +
								'<td style="text-align: left; vertical-align: middle;">' + udmData.article_name + '</td>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.quantity_units + '</td>' +
								'<td style="text-align: center; vertical-align: middle;"><input class="span2" id="fhUdm_ubicazione_input_code_' + udmData.udm_code + '" type="text" value="'+ udmData.ubicazione + '" /></td>' +
							'</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
						
					}
				}
				if (totalPages < 1) {
					totalPages = 1;
				}
				
				var footHtml = "";
				if (currentPage > 1) {
					footHtml += '<a class="previous" href="#" title="Inizio" onclick="fhUdm_search(1); return false;"><i class="icon-arrow-l"></i> << </a>';
					footHtml += '<a class="previous" href="#" title="Precedente" onclick="fhUdm_search(' + (currentPage - 1) + '); return false;"><i class="icon-arrow-l"></i> < </a>';
				}
				
				
				footHtml += '<select id="fhUdm_selectionPage" name="fhUdm_selectionPage" class="fhUdm_selectionPage" onchange="fhUdm_search(this.options[this.selectedIndex].value); return false;" >'
				for (var i = 1; i <= totalPages ; i++) { 
					if (i == currentPage) {
					footHtml += '<option style="font-weight: bold;" width="5" selected value="' + i + '" >' + i + '</option>';
					} else {
					footHtml += '<option style="font-weight: bold;" width="5" value="' + i + '" >' + i + '</option>';
					}
				}
				footHtml += '</select>';
				//footHtml +='Pagina ' + currentPage + ' di ' + totalPages;
				
				if (currentPage < totalPages) {
					footHtml += '<a class="next" href="#" title="Successiva" onclick="fhUdm_search(' + (currentPage + 1) + '); return false;"> > <i class="icon-arrow-r"></i></a>';
					footHtml += '<a class="next" href="#" title="Fine" onclick="fhUdm_search(' + (totalPages) + '); return false;"> >> <i class="icon-arrow-r"></i></a>';
				}
				jQuery(tfootElement).html(footHtml);
				jQuery(progressElement).slideUp();
				jQuery(tbodyElement).slideDown();
				// jQuery('#fhUdm_filterArticleCode').val('');
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery(progressElement).slideUp();
			}
		}
		
	);
	
}

	
	
	

function fhUdm_saveUdm() {
	
	
	var ubicazioneData = [];
	jQuery('.fhUdm_checkbox').each(function () {
		if (jQuery(this).is(":checked")) {
			var myItem = {};
			myItem.udm_code = jQuery(this).val();
			myItem.ubicazione = jQuery('#fhUdm_ubicazione_input_code_' + jQuery(this).val() ).val();
			ubicazioneData.push(myItem);
		}
	});
	
	if (ubicazioneData.length > 0) {
		jQuery.post(
			'index.php?controller=udm&task=jsonSaveUdm&type=json',
			{
				"ubicazioneData": JSON.stringify(ubicazioneData)
			},
			function (data) {
				var result = DMResponse.validateJson(data);

				if (result != false) {

					if (result.result >= 0) {

						alert('Operazione completata');
						window.location.reload();
					} else {
						alert("Si è verificato un errore (" + result.result + "): " + result.description);
					}
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);		
						
				}
			}
		);
	} else {
		alert('Nessun articolo selezionato');
	}
}			

function fhUdm_printUdm() {
	
	var udmCodeData = [];
	jQuery('.fhUdm_checkbox').each(function () {
		if (jQuery(this).is(":checked")) {
			var myItem = {};
			myItem.udm_code = jQuery(this).val();
			udmCodeData.push(myItem);
		}
	});
	
	if (udmCodeData.length > 0) {
		jQuery.post(
			'index.php?controller=udm&task=jsonPrintUdm&type=json',
			{
				"udmCodeData": JSON.stringify(udmCodeData)
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
	} else {
		alert('Nessun articolo selezionato');
	}
}


function fhUdm_deleteUdm() {

	
	var udmCodeData = [];
	jQuery('.fhUdm_checkbox').each(function () {
		if (jQuery(this).is(":checked")) {
			var myItem = {};
			myItem.udm_code = jQuery(this).val();
			udmCodeData.push(myItem);
		}
	});

	if (udmCodeData.length > 0) {	
		if (confirm("Questa operazione non è annullabile! Sicuro di voler eliminare le UDM selezionate?")) {
			jQuery.post(
				'index.php?controller=udm&task=jsonDeleteUdm&type=json',
				{
					"udmCodeData": JSON.stringify(udmCodeData)
				},
				function (data) {
					
					var result = DMResponse.validateJson(data);
					
					if ((result != false) && (result.result >= 0)) {	
						alert("UDM eliminata");
						window.location.reload();
					} else {
						alert("Si è verificato un errore (" + result.result + "): " + result.description);
					}
					
				}
			);
		}	
	} else {
	alert('Nessun articolo selezionato');
	}
	
	
}
function fhUdms_exportCSV() {

	jQuery.post(
		'index.php?controller=udm&task=jsonExportUdmsCSV&type=json',
		{
			"articleCode" : jQuery('#fhUdm_filterArticleCode').val(),
			"udmCode" : jQuery('#fhUdm_filterUdmCode').val(),
			"ubicazione": jQuery('#fhUdm_filterUbicazione').val()
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