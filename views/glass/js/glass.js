var fhGlass_data = {};
fhGlass_data.latestArticleId = -1;


/**
	Inizializza il ciclo di lettura/processamento
**/

function fhGlass_startProcess() {
	
	// jQuery('#fhGlass_scan').show();
	jQuery('#fhGlass_process').slideDown();
	jQuery('#fhGlass_article').slideUp();
	jQuery('#fhGlass_scan_code').val("").focus();
	
	fhGlass_data.latestArticleId = -1;
	
}

function fhGlass_finishProcess() {
	
	// jQuery('#fhGlass_scan').show();
	jQuery('#fhGlass_process').slideDown();
	jQuery('#fhGlass_article').slideUp();
	jQuery('#fhGlass_article_quantity').val("");
	jQuery('#fhGlass_scan_code').val("").focus();
	
	
	
	fhGlass_data.latestArticleId = -1;
	
}

/**
	Apre il popup di ricerca articoli
**/
function fhGlass_articleSearch(eanCode) {
	
	
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
	    	fhGlass_articleSelect(articleId, false);
			
			
	    }
	});
	
}

/**
	Seleziona l'articolo e apre la lavorazione su questo
**/
function fhGlass_articleSelect(articleId, batchInCode) {
	
	
	
	if (batchInCode === false) {
		batchInCode = "";
	}
	
	
	jQuery('#fhGlass_article').slideDown();
	jQuery('#fhGlass_article_data').slideUp();
	jQuery('#fhGlass_article_loading').slideDown();
	
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
				
				fhGlass_data.article = result.article;
				fhGlass_data.latestArticleId = fhGlass_data.article.article_id;
				
				//Carico i dati dell'articolo
				//jQuery('#fhGlass_scan_code').val(fhGlass_data.article.article_code);
				jQuery('#fhGlass_article_articleCode').html(fhGlass_data.article.article_code);
				jQuery('#fhGlass_article_eanCode').html(fhGlass_data.article.ean_code);
				jQuery('#fhGlass_article_packageUnits').html(fhGlass_data.article.package_units);
				jQuery('#fhGlass_article_name').html(fhGlass_data.article.name);
				jQuery('#fhGlass_article_batchInCode').val(batchInCode);
				
				jQuery('#fhGlass_article_loading').slideUp();
				jQuery('#fhGlass_article_data').slideDown();
				jQuery('#fhGlass_article_quantity').val(1);
				fhGlass_process(1);
				
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhGlass_article_loading').slideUp();
			}
		}
		
	);
	
}


/**
	Cerca di determinare se si tratta di un cartone o no: se si, carica direttamente l'articolo con il codice corrispondente, altrimenti avvia una ricerca
**/

function fhGlass_barcodeSearch() {
	
	
	jQuery('#fhGlass_scan_loading').slideDown();
	
	var barcode = jQuery('#fhGlass_scan_code').val();
	
	jQuery.post(
		'index.php?controller=glass&task=jsonCheckBarcode&type=json',
		{
			"barcode": barcode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				if (result.data.article_id > -1) {
					fhGlass_articleSelect(result.data.article_id, result.data.batch_in_code);	
				} else {
					fhGlass_articleSearch(barcode);
				}
				
				jQuery('#fhGlass_scan_loading').slideUp();
						
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhGlass_scan_loading').slideUp();
			}
		}
		
	);
	
}


/** PROCESSAMENTO **/
function fhGlass_process(stockTarget) {

	//Indipendentemente dal processType, verifico che possa andare avanti
	
	if (jQuery('#fhGlass_article_quantity').val() < 1) {
		alert("Occorre indicare il numero di confezioni da processare");
		return false;
	}
	
	//OK, provvedo a mandare tutti i dati necessari
	jQuery('#fhGlass_process').slideUp();
	jQuery('#fhGlass_progress').slideDown();
	
	jQuery.post(
		'index.php?controller=glass&task=jsonProcess&type=json',
		{
			"stockTarget": stockTarget,
			"articleId": fhGlass_data.article.article_id,
			"batchInCode": jQuery('#fhGlass_article_batchInCode').val(),
			"quantity": jQuery('#fhGlass_article_quantity').val()
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhGlass_finishProcess();
				jQuery('#fhGlass_progress').slideUp();
								
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				
				jQuery('#fhGlass_process').slideDown();
				jQuery('#fhGlass_progress').slideUp();
			}
		}
	);
	
}





