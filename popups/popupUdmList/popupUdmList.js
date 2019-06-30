var popupUdmList_data = {};
var popupUdmList_canSelect = 0;
var popupUdmList_canPrint = 0;

function popupUdmList_refreshUdms() {

	var tbodyElement = '#popupUdmLists_table tbody';
	var progressElement = '#popupUdmList .results .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();
	
	jQuery.post(
		'index.php?controller=logistics&task=jsonGetUdms&type=json',
		{
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
			
				if (result.result > 0) {
				
					udms = result.udms;
					udmsCount = udms.length;

                    var lastUdmCode = '';
					
					for (var i = 0; i < udmsCount; i++) {
						var udmData = udms[i];

                        if (udmData.udm_code == lastUdmCode) {
                            udmData.udm_code = '';
                            var showSelect = false;
                        } else {
                            lastUdmCode = udmData.udm_code;
                            var showSelect = true;							
                        }
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.udm_code + '</td>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.article_code + '</td>' +
								'<td style="text-align: left; vertical-align: middle;">' + udmData.article_name + '</td>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.quantity_units + '</td>';
								
						if (popupUdmList_canSelect && showSelect) {
							rowHtml += '<td style="text-align: center"><button class="btn btn-mini btn-primary" id="popupUdmList_btnSelectUdm_' + udmData.udm_code + '" onclick="popupUdmList_selectUdm(' + udmData.udm_code + '); return false;">Seleziona</button></td>';
						} else if (popupUdmList_canSelect) {
                            rowHtml += '<td></td>';
                        }

                        if (popupUdmList_canPrint && showSelect) {
                            rowHtml += '<td style="text-align: center"><button class="btn btn-mini" id="popupUdmList_btnPrintUdm_' + udmData.udm_code + '" onclick="popupUdmList_printUdm(' + udmData.udm_code + '); return false;">Stampa</button></td>';
                        } else if (popupUdmList_canPrint) {
                            rowHtml += '<td></td>';
                        }
						
						rowHtml += '</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
				
					jQuery(progressElement).slideUp();
					jQuery(tbodyElement).slideDown();
					
				} else {
				
					jQuery(progressElement).slideUp();
					jQuery(tbodyElement).slideDown();
					
				}
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}


function popupUdmList_search() {
	
	
	var tbodyElement = '#popupUdmLists_table tbody';
	var progressElement = '#popupUdmList .results .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();
	
	var articleCode = jQuery('#popupUdmList_filterArticleCode').val()
	
	if (articleCode == '') {
		popupUdmList_refreshUdms();
	}
	
	jQuery.post(
		'index.php?controller=logistics&task=jsonGetItemUdms&type=json',
		{
			"articleCode": articleCode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
			
				if (result.result > 0) {
				
					udms = result.udms;
					udmsCount = udms.length;

                    var lastUdmCode = '';
					
					for (var i = 0; i < udmsCount; i++) {
						var udmData = udms[i];

                        if (udmData.udm_code == lastUdmCode) {
                            udmData.udm_code = '';
                            var showSelect = false;
                        } else {
                            lastUdmCode = udmData.udm_code;
                            var showSelect = true;							
                        }
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.udm_code + '</td>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.article_code + '</td>' +
								'<td style="text-align: left; vertical-align: middle;">' + udmData.article_name + '</td>' +
								'<td style="text-align: center; vertical-align: middle;">' + udmData.quantity_units + '</td>';
								
						if (popupUdmList_canSelect && showSelect) {
							rowHtml += '<td style="text-align: center"><button class="btn btn-mini btn-primary" id="popupUdmList_btnSelectUdm_' + udmData.udm_code + '" onclick="popupUdmList_selectUdm(' + udmData.udm_code + '); return false;">Seleziona</button></td>';
						} else if (popupUdmList_canSelect) {
                            rowHtml += '<td></td>';
                        }

                        if (popupUdmList_canPrint && showSelect) {
                            rowHtml += '<td style="text-align: center"><button class="btn btn-mini" id="popupUdmList_btnPrintUdm_' + udmData.udm_code + '" onclick="popupUdmList_printUdm(' + udmData.udm_code + '); return false;">Stampa</button></td>';
                        } else if (popupUdmList_canPrint) {
                            rowHtml += '<td></td>';
                        }
						
						rowHtml += '</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
				
					jQuery(progressElement).slideUp();
					jQuery(tbodyElement).slideDown();
					
				} else {
				
					jQuery(progressElement).slideUp();
					jQuery(tbodyElement).slideDown();
					
				}
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				
			}
		}
		
	);
	
}

function popupUdmList_addArticleToUdm() {
	
	if (confirm("Questa è una operazione FORZATA, e NON controllerà la presenza di sufficienti cartoni buoni nel magazzino, ma imposterà solamente la quantità nella UDM. Continuare?")) {
		
		var udmCode = jQuery('#popupUdmList_editUdmCode').val();
		
		var type = udmCode.substring(0,3);
		
		if (type === '1') {
			var stockId = 1;
		} else {
			var stockId = 3;
		}
		jQuery.post(
		    'index.php?controller=logistics&task=jsonSetUdm&type=json',
		    {
		    	"articleCode": jQuery('#popupUdmList_editArticleCode').val(),
		    	"udmCode": jQuery('#popupUdmList_editUdmCode').val(),
		    	"quantity": jQuery('#popupUdmList_editQuantity').val(),
				"stockId": stockId,
		    	"forced": 1
		    },
		    function (data) {
		    	
		    	var result = DMResponse.validateJson(data);
		    	
		    	if ((result != false) && (result.result >= 0)) {	
		    		alert("UDM aggiornata");
		    		popupUdmList_refreshUdms();
		    	} else {
		    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
		    	}
		    	
		    }
		);
		
	}
	
}

function popupUdmList_selectUdm(udmCode) {

	DMPopup.successPopup('popupUdmList', udmCode);
	jQuery('#popupUdmList_btnSelectUdm_' + udmCode).hide();
	jQuery('#popupUdmList').modal("hide");
	
}

function popupUdmList_printUdm(udmCode) {

    jQuery.post(
        'index.php?controller=logistics&task=jsonPrintUdm&type=json',
        {
            "udmCode": udmCode
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