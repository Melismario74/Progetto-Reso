var fhGood_data = {};
fhGood_data.latestArticleId = -1;


/**
	Inizializza il ciclo di lettura/processamento
**/

function fhGood_startProcess() {
	
	// jQuery('#fhGood_scan').show();
	jQuery('#fhGood_process').slideDown();
	jQuery('#fhGood_article').slideUp();
	jQuery('#fhGood_scan_code').val("").focus();
	
	fhGood_data.latestArticleId = -1;
	
}

function fhGood_finishProcess() {
	
	// jQuery('#fhGood_scan').show();
	jQuery('#fhGood_process').slideDown();
	jQuery('#fhGood_article').slideUp();
	jQuery('#fhGood_article_quantity').val("");
	jQuery('#fhGood_scan_code').val("").focus();
	
	
	
	fhGood_data.latestArticleId = -1;
	
}

/**
	Apre il popup di ricerca articoli
**/
function fhGood_articleSearch(eanCode) {
	
	
	var myParams = "";
	if (eanCode != undefined) {
		myParams += "eanCode=" + eanCode;
	}
	
	var popupArticleSearch = DMPopup.getInstance({
	    name: 'popupArticleSearch',
	    includeCallback: function () {
	    	this.openPopup('open', myParams);
	    },
	    onSuccess: function (articleId) {
	    	fhGood_articleSelect(articleId, false);
			
			
	    }
	});
	
}

/**
	Seleziona l'articolo e apre la lavorazione su questo
**/
function fhGood_articleSelect(articleId, batchInCode) {
	
	
	
	if (batchInCode === false) {
		batchInCode = "";
	}
	
	
	jQuery('#fhGood_article').slideDown();
	jQuery('#fhGood_article_data').slideUp();
	jQuery('#fhGood_article_loading').slideDown();
	
	//Ottengo i dati dell'articolo
	jQuery.post(
		'index.php?controller=article&task=jsonLoadArticle&type=json',
		{
			"articleId": articleId,
			"batchInCode": batchInCode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhGood_data.article = result.article;
				fhGood_data.latestArticleId = fhGood_data.article.article_id;
				
				//Carico i dati dell'articolo
				//jQuery('#fhGood_scan_code').val(fhGood_data.article.article_code);
				jQuery('#fhGood_article_articleCode').html(fhGood_data.article.article_code);
				jQuery('#fhGood_article_eanCode').html(fhGood_data.article.ean_code);
				jQuery('#fhGood_article_packageUnits').html(fhGood_data.article.package_units);
				jQuery('#fhGood_article_name').html(fhGood_data.article.name);
				jQuery('#fhGood_article_batchInCode').val(batchInCode);
				
				jQuery('#fhGood_article_loading').slideUp();
				jQuery('#fhGood_article_data').slideDown();
				jQuery('#fhGood_article_quantity').val("").focus();
				
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhGood_article_loading').slideUp();
			}
		}
		
	);
	
}


/**
	Cerca di determinare se si tratta di un cartone o no: se si, carica direttamente l'articolo con il codice corrispondente, altrimenti avvia una ricerca
**/

function fhGood_barcodeSearch() {
	
	
	jQuery('#fhGood_scan_loading').slideDown();
	
	var barcode = jQuery('#fhGood_scan_code').val();
	
	jQuery.post(
		'index.php?controller=good&task=jsonCheckBarcode&type=json',
		{
			"barcode": barcode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				if (result.data.article_id > -1) {
					fhGood_articleSelect(result.data.article_id, result.data.batch_in_code);	
				} else {
					fhGood_articleSearch(barcode);
				}
				
				jQuery('#fhGood_scan_loading').slideUp();
						
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhGood_scan_loading').slideUp();
			}
		}
		
	);
	
}


/** PROCESSAMENTO **/
function fhGood_process(stockTarget) {

	//Indipendentemente dal processType, verifico che possa andare avanti
	
	if (jQuery('#fhGood_article_quantity').val() < 1) {
		alert("Occorre indicare il numero di confezioni da processare");
		return false;
	}
	
	//OK, provvedo a mandare tutti i dati necessari
	jQuery('#fhGood_process').slideUp();
	jQuery('#fhGood_progress').slideDown();
	
	jQuery.post(
		'index.php?controller=good&task=jsonProcess&type=json',
		{
			"stockTarget": stockTarget,
			"articleId": fhGood_data.article.article_id,
			"batchInCode": jQuery('#fhGood_article_batchInCode').val(),
			"quantity": jQuery('#fhGood_article_quantity').val()
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhGood_finishProcess();
				jQuery('#fhGood_progress').slideUp();
								
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				
				jQuery('#fhGood_process').slideDown();
				jQuery('#fhGood_progress').slideUp();
			}
		}
	);
	
}





