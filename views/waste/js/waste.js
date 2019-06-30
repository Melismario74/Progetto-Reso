var fhWaste_data = {};
fhWaste_data.latestArticleId = -1;


/**
	Inizializza il ciclo di lettura/processamento
**/

function fhWaste_startProcess() {
	
	// jQuery('#fhWaste_scan').show();
	jQuery('#fhWaste_process').slideDown();
	jQuery('#fhWaste_article').slideUp();
	jQuery('#fhWaste_scan_code').val("").focus();
	
	fhWaste_data.latestArticleId = -1;
	
}

function fhWaste_finishProcess() {
	
	// jQuery('#fhWaste_scan').show();
	jQuery('#fhWaste_process').slideDown();
	jQuery('#fhWaste_article').slideUp();
	jQuery('#fhWaste_article_quantity').val("");
	jQuery('#fhWaste_scan_code').val("").focus();
	
	
	
	fhWaste_data.latestArticleId = -1;
	
}

/**
	Apre il popup di ricerca articoli
**/
function fhWaste_articleSearch(eanCode) {
	
	
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
	    	fhWaste_articleSelect(articleId, false);
			
			
	    }
	});
	
}

/**
	Seleziona l'articolo e apre la lavorazione su questo
**/
function fhWaste_articleSelect(articleId, batchInCode) {
	
	
	
	if (batchInCode === false) {
		batchInCode = "";
	}
	
	
	jQuery('#fhWaste_article').slideDown();
	jQuery('#fhWaste_article_data').slideUp();
	jQuery('#fhWaste_article_loading').slideDown();
	
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
				
				fhWaste_data.article = result.article;
				fhWaste_data.latestArticleId = fhWaste_data.article.article_id;
				
				//Carico i dati dell'articolo
				//jQuery('#fhWaste_scan_code').val(fhWaste_data.article.article_code);
				jQuery('#fhWaste_article_articleCode').html(fhWaste_data.article.article_code);
				jQuery('#fhWaste_article_eanCode').html(fhWaste_data.article.ean_code);
				jQuery('#fhWaste_article_packageUnits').html(fhWaste_data.article.package_units);
				jQuery('#fhWaste_article_name').html(fhWaste_data.article.name);
				jQuery('#fhWaste_article_batchInCode').val(batchInCode);
				
				jQuery('#fhWaste_article_loading').slideUp();
				jQuery('#fhWaste_article_data').slideDown();
				jQuery('#fhWaste_article_quantity').val(1);
				fhWaste_process(2);
				
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhWaste_article_loading').slideUp();
			}
		}
		
	);
	
}


/**
	Cerca di determinare se si tratta di un cartone o no: se si, carica direttamente l'articolo con il codice corrispondente, altrimenti avvia una ricerca
**/

function fhWaste_barcodeSearch() {
	
	
	jQuery('#fhWaste_scan_loading').slideDown();
	
	var barcode = jQuery('#fhWaste_scan_code').val();
	
	jQuery.post(
		'index.php?controller=waste&task=jsonCheckBarcode&type=json',
		{
			"barcode": barcode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				if (result.data.article_id > -1) {
					fhWaste_articleSelect(result.data.article_id, result.data.batch_in_code);	
				} else {
					fhWaste_articleSearch(barcode);
				}
				
				jQuery('#fhWaste_scan_loading').slideUp();
						
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhWaste_scan_loading').slideUp();
			}
		}
		
	);
	
}


/** PROCESSAMENTO **/
function fhWaste_process(stockTarget) {

	//Indipendentemente dal processType, verifico che possa andare avanti
	
	if (jQuery('#fhWaste_article_quantity').val() < 1) {
		alert("Occorre indicare il numero di confezioni da processare");
		return false;
	}
	
	//OK, provvedo a mandare tutti i dati necessari
	jQuery('#fhWaste_process').slideUp();
	jQuery('#fhWaste_progress').slideDown();
	
	jQuery.post(
		'index.php?controller=waste&task=jsonProcess&type=json',
		{
			"stockTarget": stockTarget,
			"articleId": fhWaste_data.article.article_id,
			"batchInCode": jQuery('#fhWaste_article_batchInCode').val(),
			"quantity": jQuery('#fhWaste_article_quantity').val()
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhWaste_finishProcess();
				jQuery('#fhWaste_progress').slideUp();
								
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				
				jQuery('#fhWaste_process').slideDown();
				jQuery('#fhWaste_progress').slideUp();
			}
		}
	);
	
}





