var fhObsolete_data = {};
fhObsolete_data.latestArticleId = -1;


/**
	Inizializza il ciclo di lettura/processamento
**/

function fhObsolete_startProcess() {
	
	// jQuery('#fhObsolete_scan').show();
	jQuery('#fhObsolete_process').slideDown();
	jQuery('#fhObsolete_article').slideUp();
	jQuery('#fhObsolete_scan_code').val("").focus();
	
	fhObsolete_data.latestArticleId = -1;
	
}

function fhObsolete_finishProcess() {
	
	// jQuery('#fhObsolete_scan').show();
	jQuery('#fhObsolete_process').slideDown();
	jQuery('#fhObsolete_article').slideUp();
	jQuery('#fhObsolete_article_quantity').val("");
	jQuery('#fhObsolete_scan_code').val("").focus();
	
	
	
	fhObsolete_data.latestArticleId = -1;
	
}

/**
	Apre il popup di ricerca articoli
**/
function fhObsolete_articleSearch(eanCode) {
	
	
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
	    	fhObsolete_articleSelect(articleId, false);
			
			
	    }
	});
	
}

/**
	Seleziona l'articolo e apre la lavorazione su questo
**/
function fhObsolete_articleSelect(articleId, batchInCode) {
	
	
	
	if (batchInCode === false) {
		batchInCode = "";
	}
	
	
	jQuery('#fhObsolete_article').slideDown();
	jQuery('#fhObsolete_article_data').slideUp();
	jQuery('#fhObsolete_article_loading').slideDown();
	
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
				
				fhObsolete_data.article = result.article;
				fhObsolete_data.latestArticleId = fhObsolete_data.article.article_id;
				
				//Carico i dati dell'articolo
				//jQuery('#fhObsolete_scan_code').val(fhObsolete_data.article.article_code);
				jQuery('#fhObsolete_article_articleCode').html(fhObsolete_data.article.article_code);
				jQuery('#fhObsolete_article_eanCode').html(fhObsolete_data.article.ean_code);
				jQuery('#fhObsolete_article_packageUnits').html(fhObsolete_data.article.package_units);
				jQuery('#fhObsolete_article_name').html(fhObsolete_data.article.name);
				jQuery('#fhObsolete_article_batchInCode').val(batchInCode);
				
				jQuery('#fhObsolete_article_loading').slideUp();
				jQuery('#fhObsolete_article_data').slideDown();
				jQuery('#fhObsolete_article_quantity').val("").focus();
				
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhObsolete_article_loading').slideUp();
			}
		}
		
	);
	
}


/**
	Cerca di determinare se si tratta di un cartone o no: se si, carica direttamente l'articolo con il codice corrispondente, altrimenti avvia una ricerca
**/

function fhObsolete_barcodeSearch() {
	
	
	jQuery('#fhObsolete_scan_loading').slideDown();
	
	var barcode = jQuery('#fhObsolete_scan_code').val();
	
	jQuery.post(
		'index.php?controller=obsolete&task=jsonCheckBarcode&type=json',
		{
			"barcode": barcode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				if (result.data.article_id > -1) {
					fhObsolete_articleSelect(result.data.article_id, result.data.batch_in_code);	
				} else {
					fhObsolete_articleSearch(barcode);
				}
				
				jQuery('#fhObsolete_scan_loading').slideUp();
						
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhObsolete_scan_loading').slideUp();
			}
		}
		
	);
	
}


/** PROCESSAMENTO **/
function fhObsolete_process(stockTarget) {

	//Indipendentemente dal processType, verifico che possa andare avanti
	
	if (jQuery('#fhObsolete_article_quantity').val() < 1) {
		alert("Occorre indicare il numero di confezioni da processare");
		return false;
	}
	
	//OK, provvedo a mandare tutti i dati necessari
	jQuery('#fhObsolete_process').slideUp();
	jQuery('#fhObsolete_progress').slideDown();
	
	jQuery.post(
		'index.php?controller=obsolete&task=jsonProcess&type=json',
		{
			"stockTarget": stockTarget,
			"articleId": fhObsolete_data.article.article_id,
			"batchInCode": jQuery('#fhObsolete_article_batchInCode').val(),
			"quantity": jQuery('#fhObsolete_article_quantity').val()
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhObsolete_finishProcess();
				jQuery('#fhObsolete_progress').slideUp();
								
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				
				jQuery('#fhObsolete_process').slideDown();
				jQuery('#fhObsolete_progress').slideUp();
			}
		}
	);
	
}





