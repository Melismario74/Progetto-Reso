var popupArticle_data = {};
var popupArticle_aclArticleManage = false;

function popupArticle_loadArticle(articleId) {

	jQuery.post(
		'index.php?controller=article&task=jsonLoadArticle&type=json',
		{
			"articleId": articleId,
			"getStocksData": 1,
			"getUdmsData": 1
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				popupArticle_data.article = result.article;
			
				//Carico i dati dell'articolo
				jQuery('#popupArticle_label').html(popupArticle_data.article.name);
				jQuery('#popupArticle_articleImage img').attr('src', popupArticle_data.article.image_url);
				jQuery('#popupArticle_articleCode').html(popupArticle_data.article.article_code);
				jQuery('#popupArticle_eanCode').html(popupArticle_data.article.ean_code);
				jQuery('#popupArticle_editQualityMessage').html(popupArticle_data.article.quality_message);
				jQuery('#popupArticle_editQualityBatchInCodes').html(popupArticle_data.article.quality_batch_in_codes);
				
				var stocks = popupArticle_data.article.stocks;
				var stocksCount = stocks.length;
				
				var tbodyElement = '#popupArticle_articleStocks tbody';
				
				jQuery(tbodyElement).html("");
				
				for (var i = 0; i < stocksCount; i++) {
					var stockData = stocks[i];
					
					var rowHtml = 
						'<tr>' +
							'<td style="text-align: left">' + stockData.name + '</td>' +
							'<td style="text-align: center">' +
								stockData.total_units +
							'</td>' +
							'<td style="text-align: center">' +
								stockData.total_packages +
							'</td>' +
						'</tr>';
					
					jQuery(tbodyElement).append(rowHtml);
				}
				
				var listUdms = popupArticle_data.article.listUdms;
				var listUdmsCount = listUdms.length;
				
				var tbodyElement = '#popupArticle_articleUdms tbody';
				
				jQuery(tbodyElement).html("");
				
				for (var i = 0; i < listUdmsCount; i++) {
					if( listUdms[i] == []){
						continue;
					} else {
					var listUdmsData = listUdms[i];
					}
					
					
					var rowHtml = 
						'<tr>' +
							'<td style="text-align: left; vertical-align: middle;">' + listUdmsData.udm_code + '</td>' +
							'<td style="text-align: center; vertical-align: middle;"><input type="text" style="width: 100px; margin: 0px;" id="popupArticle_articleUdms_' + listUdmsData.udm_id + '" value="' + listUdmsData.quantity_units + '" /></td>' +
							'<td style="text-align: center; vertical-align: middle;"><button class="btn btn-success" onclick="popupArticle_updateArticleUdm(' + listUdmsData.udm_id + '); return false;"><i class="icon-white icon-ok"></i></button></td>' +
						'</tr>';
					
					jQuery(tbodyElement).append(rowHtml);
					
				}
				
				popupArticle_loadArticleMovements(articleId);
				popupArticle_loadArticleBatchIns(articleId);
				popupArticle_loadArticleBatchOuts(articleId);
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}

function popupArticle_loadArticleMovements(articleId, currentPage) {
	
	if (currentPage == undefined) {
		currentPage = 1;
	}
	
	jQuery.post(
		'index.php?controller=article&task=jsonGetArticleMovements&type=json',
		{
			"articleId": articleId,
			"page": currentPage,
			"limit": 15
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				popupArticle_data.article.movements = result.movements;
				
				var totalPages = result.result; 
				
				var tbodyElement = '#popupArticle_articleMovements tbody';
				var tfootElement = '#popupArticle_articleMovements tfoot td';
				
				if (totalPages > 0) {
					var movements = popupArticle_data.article.movements;
					var movementsCount = movements.length; 
					
					jQuery(tbodyElement).html("");
					
					for (var i = 0; i < movementsCount; i++) {
						var movementData = movements[i];
						
						var movementActionElement = '';
						if (jQuery('#popupArticle_articleMovements_actions').length > 0) {
							movementActionElement = '<td style="text-align: center; vertical-align: middle;"><button class="btn btn-mini btn-danger" onclick="popupArticle_deleteMovement(' + movementData.movement_id + '); return false;"><i class="icon-white icon-remove"></i></button></td>';
						}
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center">' + movementData.movement_id + '</td>' +
								'<td style="text-align: center">' + movementData.created_date_str + '</td>' +
								'<td style="text-align: center">' +	movementData.movement_type + '</td>' +
								'<td style="text-align: center">' +	movementData.stock.name + '</td>' +
								'<td style="text-align: right">' +	movementData.quantity_units + '</td>' +
								'<td style="text-align: right">' +	movementData.quantity_packages + '</td>' +
								'<td style="text-align: center">' +	movementData.created_by_str + '</td>' +
								movementActionElement +
							'</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
				
					if (totalPages < 1) {
						totalPages = 1;
					}
					
					var footHtml = "";
					if (currentPage > 1) {
						footHtml += '<a class="previous" href="#" onclick="popupArticle_loadArticleMovements(' + articleId + ',' + (currentPage - 1) + '); return false;"><i class="icon-arrow-l"></i> Precedente</a>';
					}
					
					footHtml += 'Pagina ' + currentPage + ' di ' + totalPages;
					
					if (currentPage < totalPages) {
						footHtml += '<a class="next" href="#" onclick="popupArticle_loadArticleMovements(' + articleId + ',' + (currentPage + 1) + '); return false;">Successiva <i class="icon-arrow-r"></i></a>';
					}
					
					jQuery(tfootElement).html(footHtml);
				} else {
					jQuery(tbodyElement).html("");
				}
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}

function popupArticle_loadArticleBatchOuts(articleId, currentPage) {
	
	if (currentPage == undefined) {
		currentPage = 1;
	}
	
	jQuery.post(
		'index.php?controller=article&task=jsonGetArticleBatchOuts&type=json',
		{
			"articleId": articleId,
			"page": currentPage,
			"limit": 15
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				popupArticle_data.article.batchOuts = result.batchOuts;
				
				var totalPages = result.result; 
				
				if (totalPages > 0) {
					var batchOuts = popupArticle_data.article.batchOuts;
					var batchOutsCount = batchOuts.length; 
					
					var tbodyElement = '#popupArticle_articleBatchOuts tbody';
					var tfootElement = '#popupArticle_articleBatchOuts tfoot td';
					
					jQuery(tbodyElement).html("");
					
					for (var i = 0; i < batchOutsCount; i++) {
						var batchOutData = batchOuts[i];
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center">' + batchOutData.batch_out_code + '</td>' +
								'<td style="text-align: center">' + batchOutData.batch_in_code + '</td>' +
								'<td style="text-align: center">' + batchOutData.quantity + '</td>' +
								'<td style="text-align: center"><button class="btn btn-warning btn-mini" onclick="popupArticle_printArticleBatchOutLabel(' + batchOutData.batch_out_id + '); return false;">Stampa etichetta</button></td>';
								
							if (popupArticle_aclArticleManage) {
								rowHtml = rowHtml + '<td style="text-align: center"><button class="btn btn-warning btn-mini" onclick="popupArticle_editArticleBatchOut(' + batchOutData.batch_out_id + ',' + batchOutData.batch_in_id + ',' + batchOutData.quantity + '); return false;"><i class="icon-white icon-edit"></i> Modifica</button>';
							} 
							
						rowHtml = rowHtml +
							'</tr>';
						
						jQuery(tbodyElement).append(rowHtml);
					}
				
					if (totalPages < 1) {
						totalPages = 1;
					}
					
					var footHtml = "";
					if (currentPage > 1) {
						footHtml += '<a class="previous" href="#" onclick="popupArticle_loadArticleBatchOuts(' + articleId + ',' + (currentPage - 1) + '); return false;"><i class="icon-arrow-l"></i> Precedente</a>';
					}
					
					footHtml += 'Pagina ' + currentPage + ' di ' + totalPages;
					
					if (currentPage < totalPages) {
						footHtml += '<a class="next" href="#" onclick="popupArticle_loadArticleBatchOuts(' + articleId + ',' + (currentPage + 1) + '); return false;">Successiva <i class="icon-arrow-r"></i></a>';
					}
					
					jQuery(tfootElement).html(footHtml);
				}
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}

function popupArticle_loadArticleBatchIns(articleId, currentPage) {
	
	if (currentPage == undefined) {
		currentPage = 1;
	}
	
	jQuery.post(
		'index.php?controller=article&task=jsonGetArticleBatchIns&type=json',
		{
			"articleId": articleId,
			"page": currentPage,
			"limit": 15
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				popupArticle_data.article.batchIns = result.batchIns;
				
				var totalPages = result.result; 
				
				if (totalPages > 0) {
					var batchIns = popupArticle_data.article.batchIns;
					var batchInsCount = batchIns.length; 
					
					var tbodyElement = '#popupArticle_articleBatchIns tbody';
					var tfootElement = '#popupArticle_articleBatchIns tfoot td';
					
					jQuery(tbodyElement).html("");
					
					for (var i = 0; i < batchInsCount; i++) {
						var batchInData = batchIns[i];
						
						var rowHtml = 
							'<tr>' +
								'<td style="text-align: center">' + batchInData.batch_in_code + '</td>' +
								'<td style="text-align: center">' + batchInData.quantity + '</td>';
								
							if (popupArticle_aclArticleManage) {
								rowHtml = rowHtml + '<td style="text-align: center"><button class="btn btn-mini btn-warning" onclick="popupArticle_editArticleBatchIn(' + batchInData.batch_in_id + ',' + batchInData.quantity + '); return false;"><i class="icon-white icon-edit"></i> Modifica</button>';
							} 
							
						rowHtml = rowHtml +
							'</tr>'; 
						
						jQuery(tbodyElement).append(rowHtml);
					}
				
					if (totalPages < 1) {
						totalPages = 1;
					}
					
					var footHtml = "";
					if (currentPage > 1) {
						footHtml += '<a class="previous" href="#" onclick="popupArticle_loadArticleBatchIns(' + articleId + ',' + (currentPage - 1) + '); return false;"><i class="icon-arrow-l"></i> Precedente</a>';
					}
					
					footHtml += 'Pagina ' + currentPage + ' di ' + totalPages;
					
					if (currentPage < totalPages) {
						footHtml += '<a class="next" href="#" onclick="popupArticle_loadArticleBatchIns(' + articleId + ',' + (currentPage + 1) + '); return false;">Successiva <i class="icon-arrow-r"></i></a>';
					}
					
					jQuery(tfootElement).html(footHtml);
				} else {
					jQuery(tbodyElement).html("");
				}
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}

function popupArticle_editArticleBatchIn(batchInId, quantity) {

	var newQuantity = prompt('Inserisci la nuova quantità', quantity);
	
	if (newQuantity != null && newQuantity != "") {
		jQuery.post(
			'index.php?controller=article&task=jsonUpdateArticleBatchIn&type=json',
			{
				"articleId": popupArticle_data.article.article_id,
				"batchInId": batchInId,
				"quantity": newQuantity
			},
			function (data) {
				
				var result = DMResponse.validateJson(data);
				
				if ((result != false) && (result.result >= 0)) {	
					alert("Articolo aggiornato");
					popupArticle_loadArticleBatchIns(popupArticle_data.article.article_id);
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
				}
				
			}
		);
	}

}

function popupArticle_editArticleBatchOut(batchOutId, batchInId, quantity) {

	var newQuantity = prompt('Inserisci la nuova quantità', quantity);
	
	if (newQuantity != null && newQuantity != "") {
		jQuery.post(
			'index.php?controller=article&task=jsonUpdateArticleBatchOut&type=json',
			{
				"articleId": popupArticle_data.article.article_id,
				"batchOutId": batchOutId,
				"batchInId": batchInId,
				"quantity": newQuantity
			},
			function (data) {
				
				var result = DMResponse.validateJson(data);
				
				if ((result != false) && (result.result >= 0)) {	
					alert("Articolo aggiornato");
					popupArticle_loadArticleBatchOuts(popupArticle_data.article.article_id);
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
				}
				
			}
		);
	}

}

function popupArticle_deleteImage() {
	
	if (popupArticle_data.article.article_id == undefined) {
		return false;
	}
	
	if (confirm("Confermi l'eliminazione dell'immagine dall'articolo?")) {
		jQuery.post(
			'index.php?controller=article&task=jsonDeleteArticleImage&type=json',
			{
				"articleId": popupArticle_data.article.article_id
			},
			function (data) {
				
				var result = DMResponse.validateJson(data);
				
				if ((result != false) && (result.result >= 0)) {	
					alert("Immagine eliminata");
					popupArticle_loadArticle(popupArticle_data.article.article_id)
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
				}
				
			}
		);
	}
	
}

function popupArticle_deleteMovement(movementId) {

	if (confirm("Questa operazione non è annullabile! Sicuro di voler eliminare il movimento?")) {
		alert("Attenzione, NON verranno ricalcolate le quantità dell'articolo nei lotti di uscita, UDM o DDT. Occorrerà farlo manualmente!");
		jQuery.post(
			'index.php?controller=stock&task=jsonDeleteMovement&type=json',
			{
				"movementId": movementId
			},
			function (data) {
				
				var result = DMResponse.validateJson(data);
				
				if ((result != false) && (result.result >= 0)) {	
					alert("Movimento eliminato");
					popupArticle_loadArticle(popupArticle_data.article.article_id);
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
				}
				
			}
		);
	}
	
}

function popupArticle_addMovement() {

	var params = 'articleId=' + popupArticle_data.article.article_id;
	
	var popupMovement = DMPopup.getInstance({
		name: 'popupMovement',
		includeCallback: function () {
			this.openPopup('open', params);
		},
		onSuccess: function (data) {
			popupArticle_loadArticle(popupArticle_data.article.article_id);
		}
	});
	
}

function popupArticle_saveQuality() {
	
	jQuery.post(
	    'index.php?controller=article&task=jsonSaveArticleQuality&type=json',
	    {
	    	"articleId": popupArticle_data.article.article_id,
	    	"qualityMessage": jQuery('#popupArticle_editQualityMessage').val(),
	    	"qualityBatchInCodes": jQuery('#popupArticle_editQualityBatchInCodes').val()
	    },
	    function (data) {
	    	
	    	var result = DMResponse.validateJson(data);
	    	
	    	if ((result != false) && (result.result >= 0)) {	
	    		alert("Comunicazioni qualità salvate");
	    		popupArticle_loadArticle(popupArticle_data.article.article_id);
	    	} else {
	    		alert("Si è verificato un errore (" + result.result + "): " + result.description);
	    	}
	    	
	    }
	);
	
}

function popupArticle_printArticleBatchOutLabel(batchOutId) {

	jQuery.post(
		'index.php?controller=article&task=jsonPrintArticleBatchOutLabel&type=json',
		{
			"articleId": popupArticle_data.article.article_id,
			"batchOutId": batchOutId
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
				
				window.open(result.data.label_url, '_blank');
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	

}

function popupArticle_updateArticleUdm(udmId) {

	if (confirm("Vuoi aggiornare la quantità di cartoni dislocati in UDM?")) {
		jQuery.post(
			'index.php?controller=article&task=jsonUpdateArticleUdmQuantity&type=json',
			{
				"articleId": popupArticle_data.article.article_id,
				"udmId": udmId,
				"quantity": jQuery('#popupArticle_articleUdms_' + udmId).val()
			},
			function (data) {
				var result = DMResponse.validateJson(data);
				
				if ((result != false) && (result.result >= 0)) {	
					
					alert('UDM aggiornata');
					popupArticle_loadArticle(popupArticle_data.article.article_id);
					
				} else {
					alert("Si è verificato un errore (" + result.result + "): " + result.description);
				}
			}
			
		);
	}
	
}