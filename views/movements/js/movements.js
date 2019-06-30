function fhMovements_search(currentPage) {
	
	if (currentPage == undefined) {
		currentPage = 1;
	}
	
	var tbodyElement = '#fhMovements_results_table tbody';
	var tfootElement = '#fhMovements_results_table tfoot td';
	var progressElement = '#fhMovements .search .progress';
	
	jQuery(tbodyElement).html("").hide();
	jQuery(progressElement).slideDown();
	

	jQuery.post(
		'index.php?controller=movement&task=jsonGetMovements&type=json',
		{
			"movementDateFrom": jQuery('#fhMovements_filterMovementDateFrom').val(),
			"movementDateTo": jQuery('#fhMovements_filterMovementDateTo').val(),
			"batchInCode": jQuery('#fhMovements_filterBatchInCode').val(),
			"batchOutCode": jQuery('#fhMovements_filterBatchOutCode').val(),
			"articleCode": jQuery('#fhMovements_filterArticleCode').val(),
			"eanCode": jQuery('#fhMovements_filterEanCode').val(),
			"movementType": jQuery('#fhMovements_filterMovementType').val(),
			"userId": jQuery('#fhMovements_filterUser').val(),
			"stockId": jQuery('#fhMovements_filterStock').val(),
			"page": currentPage,
			"getArticleData": 1,
			"getUserData": 1,
			"getMovementDetails": 1,
			"limit": 30
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				totalPages = result.result;
				
				if (totalPages > 0) {
					var movements = result.movements;
					var movementsCount = movements.length;
					
					for (var i = 0; i < movementsCount; i++) {
						var movementData = movements[i];
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center">' + movementData.movement_id + '</td>' +
								'<td style="text-align: center">' + movementData.created_date_str + '</td>' +
								'<td style="text-align: center">' + movementData.movement_type_str + '</td>' +
								'<td style="text-align: center">' + movementData.article.article_code + '</td>' +
								'<td style="text-align: left">' + movementData.article.name + '</td>' +
								'<td style="text-align: center">' + movementData.batch_in_code + '</td>' +
								'<td style="text-align: center">' + movementData.batch_out_code + '</td>' +
								'<td style="text-align: center">' + movementData.user.name + '</td>' +
							'</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
						
						var details = movementData.details
						var detailsCount = details.length;
						
						for (var j = 0; j < detailsCount; j++) {
							var detailData = details[j];
						
							rowHtml =
								'<tr class="detail">' +
									'<td colspan="5"></td>' +
									'<td style="text-align: right; font-weight: bold; border-top: 1px solid #999;">' + detailData.stock_name + '</td>' +
									'<td style="text-align: center; border-top: 1px solid #999;">Cart. <span class="label" style="margin-right: 3px">' + detailData.quantity_packages + '</span> </td>' +
									'<td style="text-align: center; border-top: 1px solid #999;">Conf. <span class="label" style="margin-right: 3px">' + detailData.quantity_units + '</span></td>' +
								'</tr>';
								
							jQuery(tbodyElement).append(rowHtml);
						}
					}
				}
				
				if (totalPages < 1) {
					totalPages = 1;
				}
				
				var footHtml = "";
				if (currentPage > 1) {
					footHtml += '<a class="previous" href="#" title="Inizio" onclick="fhMovements_search(1); return false;"><i class="icon-arrow-l"></i> << </a>';
					footHtml += '<a class="previous" href="#" title="Precedente" onclick="fhMovements_search(' + (currentPage - 1) + '); return false;"><i class="icon-arrow-l"></i> < </a>';
				}
				
				footHtml += '<select id="fhMovements_selectionPage" name="fhMovements_selectionPage" class="fhMovements_selectionPage" onchange="fhMovements_search(this.options[this.selectedIndex].value); return false;" >'
				for (var i = 1; i <= totalPages ; i++) { 
					if (i == currentPage) {
					footHtml += '<option style="font-weight: bold;" width="5" selected value="' + i + '" >' + i + '</option>';
					} else {
					footHtml += '<option style="font-weight: bold;" width="5" value="' + i + '" >' + i + '</option>';
					}
				}
				footHtml += '</select>';
				
				if (currentPage < totalPages) {
					footHtml += '<a class="next" href="#" title="Successiva" onclick="fhMovements_search(' + (currentPage + 1) + '); return false;"> > <i class="icon-arrow-r"></i></a>';
					footHtml += '<a class="next" href="#" title="Fine" onclick="fhMovements_search(' + (totalPages) + '); return false;"> >> <i class="icon-arrow-r"></i></a>';
				}
				
				jQuery(tfootElement).html(footHtml);
				
				jQuery(progressElement).slideUp();
				jQuery(tbodyElement).slideDown();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery(progressElement).slideUp();
			}
		}
		
	);
	
}

function fhMovements_selectArticle(articleId) {

	var params = 'articleId=' + articleId;
	
	var popupArticle = DMPopup.getInstance({
		name: 'popupArticle',
		includeCallback: function () {
			this.openPopup('open', params);
		}
	});

}

function fhMovements_export() {

	jQuery.post(
		'index.php?controller=movement&task=jsonExportMovements&type=json',
		{
			"movementDateFrom": jQuery('#fhMovements_filterMovementDateFrom').val(),
			"movementDateTo": jQuery('#fhMovements_filterMovementDateTo').val(),
			"batchInCode": jQuery('#fhMovements_filterBatchInCode').val(),
			"batchOutCode": jQuery('#fhMovements_filterBatchOutCode').val(),
			"articleCode": jQuery('#fhMovements_filterArticleCode').val(),
			"eanCode": jQuery('#fhMovements_filterEanCode').val(),
			"movementType": jQuery('#fhMovements_filterMovementType').val(),
			"userId": jQuery('#fhMovements_filterUser').val()
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

function fhMovements_exportCSV() {

	jQuery.post(
		'index.php?controller=movement&task=jsonExportMovementsCSV&type=json',
		{
			"movementDateFrom": jQuery('#fhMovements_filterMovementDateFrom').val(),
			"movementDateTo": jQuery('#fhMovements_filterMovementDateTo').val(),
			"batchInCode": jQuery('#fhMovements_filterBatchInCode').val(),
			"batchOutCode": jQuery('#fhMovements_filterBatchOutCode').val(),
			"articleCode": jQuery('#fhMovements_filterArticleCode').val(),
			"eanCode": jQuery('#fhMovements_filterEanCode').val(),
			"movementType": jQuery('#fhMovements_filterMovementType').val(),
			"userId": jQuery('#fhMovements_filterUser').val()
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