var popupUdmListInvoice_data = {};
var popupUdmList_stockId = 0;

function popupUdmListInvoice_refreshUdms(stockId) {

	var tbodyElement = '#popupUdmListsInvoice_table tbody';
	var progressElement = '#popupUdmListInvoice .results .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();
	
	jQuery.post(
		'index.php?controller=logistics&task=jsonGetUdmsStock&type=json',
		{
			"stockId": stockId
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

                       
						var rowHtml = 
						'<tr>' +
				    		'<td style="text-align: center"><input type="checkbox" id="popupUdmListInvoice_checkbox" class="popupUdmListInvoice_checkbox" value="' + udmData.udm_code + '" /></td>' +
							'<td style="text-align: center; vertical-align: middle;">' + udmData.udm_code + '</td>' +
							'<td style="text-align: center; vertical-align: middle;" id="popupUdmListInvoice_article_' + udmData.udm_code + '">' + udmData.article_code + '</td>' +
							'<td style="text-align: left; vertical-align: middle;">' + udmData.article_name + '</td>' +
							'<td style="text-align: center; vertical-align: middle;"><input type="text" style="width: 50px" id="popupUdmListInvoice_quantity_' +udmData.udm_code + '" value="' +
				    		udmData.quantity_units  + '" /></td>' +
							'<td style="text-align: center; vertical-align: middle;"><input class="span2" id="popupUdmListInvoice_input_code_' + udmData.udm_code + '" type="text" value="'+ udmData.ubicazione + '" /></td>' +
						'</tr>';
						
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


function popupUdmListInvoice_search() {
	
	
	var tbodyElement = '#popupUdmListsInvoice_table tbody';
	var progressElement = '#popupUdmListInvoice .results .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();
	
	var articleCode = jQuery('#popupUdmListInvoice_filterArticleCode').val()
	
	if (articleCode == '') {
		popupUdmListInvoice_refreshUdms();
	}
	
	jQuery.post(
		'index.php?controller=logistics&task=jsonGetUdmsStockItem&type=json',
		{
			"articleCode": articleCode,
			"stockId": popupUdmList_stockId
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

                        			
							var rowHtml = 
						'<tr>' +
				    		'<td style="text-align: center"><input type="checkbox" id="popupUdmListInvoice_checkbox" class="popupUdmListInvoice_checkbox" value="' + udmData.udm_code + '" /></td>' +
							'<td style="text-align: center; vertical-align: middle;">' + udmData.udm_code + '</td>' +
							'<td style="text-align: center; vertical-align: middle;"  id="popupUdmListInvoice_article_' + udmData.udm_code + '">' + udmData.article_code + '</td>' +
							'<td style="text-align: left; vertical-align: middle;">' + udmData.article_name + '</td>' +
							'<td style="text-align: center; vertical-align: middle;"><input type="text" style="width: 50px" id="popupUdmListInvoice_quantity_' + udmData.udm_code + '" value="' +
				    		udmData.quantity_units  + '" /></td>' +
							'<td style="text-align: center; vertical-align: middle;"><input class="span2" id="popupUdmListInvoice_input_code_' + udmData.udm_code + '" type="text" value="'+ udmData.ubicazione + '" /></td>' +
						'</tr>';
						
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


function popupUdmListInvoice_addArticles() {
	
	
	var udmCodeData = [];
	jQuery('.popupUdmListInvoice_checkbox').each(function () {
		if (jQuery(this).is(":checked")) {
			var myItem = {};
			myItem.udm_code = jQuery(this).val();
			myItem.quantity = jQuery('#popupUdmListInvoice_quantity_' + jQuery(this).val() ).val();
			myItem.article_code = jQuery('#popupUdmListInvoice_article_' + jQuery(this).val() ).html();
			udmCodeData.push(myItem);
		}
	});	

	DMPopup.successPopup('popupUdmListInvoice', udmCodeData);
	jQuery('#popupUdmListInvoice').modal("hide");
	
}
