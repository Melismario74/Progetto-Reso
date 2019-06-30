var popupUdmListOrder_data = {};
var popupUdmList_stockId = 0;

function popupUdmListOrder_refreshUdms(stockId) {

	var tbodyElement = '#popupUdmListsOrder_table tbody';
	var progressElement = '#popupUdmListOrder .results .progress';
	
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
				    		'<td style="text-align: center"><input type="checkbox" id="popupUdmListOrder_checkbox" class="popupUdmListOrder_checkbox" value="' + udmData.udm_code + '" /></td>' +
							'<td style="text-align: center; vertical-align: middle;">' + udmData.udm_code + '</td>' +
							'<td style="text-align: center; vertical-align: middle;" id="popupUdmListOrder_article_' + udmData.udm_code + '">' + udmData.article_code + '</td>' +
							'<td style="text-align: left; vertical-align: middle;">' + udmData.article_name + '</td>' +
							'<td style="text-align: center; vertical-align: middle;"><input type="text" style="width: 50px" id="popupUdmListOrder_quantity_' +udmData.udm_code + '" value="' +
				    		(udmData.quantity_units - udmData.committed_units) + '" /></td>' +
							'<td style="text-align: center; vertical-align: middle;"><input class="span2" id="popupUdmListOrder_input_code_' + udmData.udm_code + '" type="text" value="'+ udmData.ubicazione + '" /></td>' +
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


function popupUdmListOrder_search() {
	
	
	var tbodyElement = '#popupUdmListsOrder_table tbody';
	var progressElement = '#popupUdmListOrder .results .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();
	
	var articleCode = jQuery('#popupUdmListOrder_filterArticleCode').val()
	
	if (articleCode == '') {
		popupUdmListOrder_refreshUdms();
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
				    		'<td style="text-align: center"><input type="checkbox" id="popupUdmListOrder_checkbox" class="popupUdmListOrder_checkbox" value="' + udmData.udm_code + '" /></td>' +
							'<td style="text-align: center; vertical-align: middle;">' + udmData.udm_code + '</td>' +
							'<td style="text-align: center; vertical-align: middle;"  id="popupUdmListOrder_article_' + udmData.udm_code + '">' + udmData.article_code + '</td>' +
							'<td style="text-align: left; vertical-align: middle;">' + udmData.article_name + '</td>' +
							'<td style="text-align: center; vertical-align: middle;"><input type="text" style="width: 50px" id="popupUdmListOrder_quantity_' + udmData.udm_code + '" value="' +
				    		(udmData.quantity_units - udmData.committed_units)  + '" /></td>' +
							'<td style="text-align: center; vertical-align: middle;"><input class="span2" id="popupUdmListOrder_input_code_' + udmData.udm_code + '" type="text" value="'+ udmData.ubicazione + '" /></td>' +
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


function popupUdmListOrder_addArticles() {
	
	
	var udmCodeData = [];
	jQuery('.popupUdmListOrder_checkbox').each(function () {
		if (jQuery(this).is(":checked")) {
			var myItem = {};
			myItem.udm_code = jQuery(this).val();
			myItem.quantity = jQuery('#popupUdmListOrder_quantity_' + jQuery(this).val() ).val();
			myItem.article_code = jQuery('#popupUdmListOrder_article_' + jQuery(this).val() ).html();
			udmCodeData.push(myItem);
		}
	});	

	DMPopup.successPopup('popupUdmListOrder', udmCodeData);
	jQuery('#popupUdmListOrder').modal("hide");
	
}
