var fhRecondition_data = {};
fhRecondition_data.latestArticleId = -1;

/** Chargelist **/

function fhRecondition_chargelistChoice() {
	//modifica apportata da mario
	jQuery.post(
		'index.php?controller=chargelist&task=jsonLoadChargelist&type=json',
		{
			"chargelistId": 43 //chargelistId
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhRecondition_data.chargelist = result.chargelist;
				
				jQuery('#fhRecondition_chargelist_code').html("000000001");//fhRecondition_data.chargelist.chargelist_code
				jQuery('#fhRecondition_chargelist_date').html("25\/01\/2013");//fhRecondition_data.chargelist.chargelist_date_str
				jQuery('#fhRecondition_chargelist_choice').slideUp();
				jQuery('#fhRecondition_chargelist_loading').slideUp();
				jQuery('#fhRecondition_chargelist_loaded').slideDown();
				
				fhRecondition_startProcess();
			}
		}
	);
	//fine modifica apportata da Mario
	
	//var popupChargelistSearch = DMPopup.getInstance({
	//    name: 'popupChargelistSearch',
	//    includeCallback: function () {
	//    	this.openPopup('open', '');
	//    },
	//    onSuccess: function (chargelistId) {
	//    	jQuery('#fhRecondition_chargelist_choice').slideUp();
	//		fhRecondition_chargelistSelect(chargelistId);
	//    }
	//});
	
}

/**
	Attiva la chargelist selezionata
**/
/** Commentata da Mario 
function fhRecondition_chargelistSelect(chargelistId) {
	
	if (fhRecondition_data.chargelist != undefined) {
		alert('Non puoi modificare la lista di carico di una sessione attiva');
		return false;
	}
	
	jQuery('#fhRecondition_chargelist_loading').slideDown();
	jQuery('#fhRecondition_chargelist_loaded').slideUp();
	
	//Ottengo i dati della chargelist
	jQuery.post(
		'index.php?controller=chargelist&task=jsonLoadChargelist&type=json',
		{
			"chargelistId": chargelistId
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhRecondition_data.chargelist = result.chargelist;
				
				jQuery('#fhRecondition_chargelist_code').html(fhRecondition_data.chargelist.chargelist_code);
				jQuery('#fhRecondition_chargelist_date').html(fhRecondition_data.chargelist.chargelist_date_str);
				
				jQuery('#fhRecondition_chargelist_loading').slideUp();
				jQuery('#fhRecondition_chargelist_loaded').slideDown();
				
				fhRecondition_startProcess();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhRecondition_chargelist_loading').slideUp();
			}
		}
		
	);
	
}
**/
/**
	Inizializza il ciclo di lettura/processamento
**/
function fhRecondition_startProcess() {
	
	jQuery('#fhRecondition_scan').show();
	jQuery('#fhRecondition_process').slideDown();
	jQuery('#fhRecondition_article').slideUp();
	jQuery('#fhRecondition_scan_code').val("").focus();
	
	fhRecondition_data.latestArticleId = -1;
	
}

/**
	Apre il popup di ricerca articoli
**/
function fhRecondition_articleSearch(eanCode) {
	
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
	    	fhRecondition_articleSelect(articleId, false);
	    }
	});
	
}

/**
	Seleziona l'articolo e apre la lavorazione su questo
**/
function fhRecondition_articleSelect(articleId, isPackage, batchInCode) {

	if (isPackage == undefined) {
		isPackage = false;
	}
	if (batchInCode == undefined) {
		batchInCode = '';
	}

	jQuery('#fhRecondition_article').slideDown();
	
	jQuery('#fhRecondition_article_data').slideUp();
	jQuery('#fhRecondition_article_qualityalerts').slideUp();
	jQuery('#fhRecondition_article_chargelistalerts').hide();
	jQuery('#fhRecondition_article_loading').slideDown();
	
	//Ottengo i dati dell'articolo
	jQuery.post(
		'index.php?controller=article&task=jsonLoadArticle&type=json',
		{
			"articleId": articleId,
			"checkChargelist": 1,
			"chargelistId": fhRecondition_data.chargelist.chargelist_id
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhRecondition_data.article = result.article;
				fhRecondition_data.article.isPackage = isPackage;
				fhRecondition_data.latestArticleId = fhRecondition_data.article.article_id;
				
				//Carico i dati dell'articolo
				jQuery('#fhRecondition_article_articleCode').html(fhRecondition_data.article.article_code);
				jQuery('#fhRecondition_article_eanCode').html(fhRecondition_data.article.ean_code);
				jQuery('#fhRecondition_article_packageUnits').html(fhRecondition_data.article.package_units);
				jQuery('#fhRecondition_article_packageCode').html(fhRecondition_data.article.package_code);
				if (fhRecondition_data.article.package_description != '') {
					jQuery('#fhRecondition_article_packageCode').append(' - ' + fhRecondition_data.article.package_description);
				}
				jQuery('#fhRecondition_article_name').html(fhRecondition_data.article.name);
				jQuery('#fhRecondition_article_batchInCode').val(batchInCode);
				jQuery('#fhRecondition_article_image').attr('src', fhRecondition_data.article.image_url);
				
				if (fhRecondition_data.article.isPackage) {
					jQuery('#fhRecondition_article_quantity').val(fhRecondition_data.article.package_units).attr('readonly', true);
					jQuery('#fhRecondition_article_isPackage').prop('checked', true);
				} else {
					jQuery('#fhRecondition_article_quantity').val(0).attr('readonly', false);
					jQuery('#fhRecondition_article_isPackage').removeAttr('checked');
				}
				
				//Verifico se mostrare un alert per la mancanza nella lista di carico
				jQuery('#fhRecondition_article_forceChargelistInsert').removeAttr('checked');
				if (fhRecondition_data.article.chargelist.quantity < 0) { 
					jQuery('#fhRecondition_article_chargelistalerts').show();
				}
				
				//Nel frattempo faccio i controlli di qualità
				fhRecondition_articleCheckQuality(articleId, batchInCode);
	
				jQuery('#fhRecondition_article_loading').slideUp();
				jQuery('#fhRecondition_article_data').slideDown();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhRecondition_article_loading').slideUp();
			}
		}
		
	);
	
}

function fhRecondition_articleCheckQuality(articleId, batchInCode) {

	jQuery('#fhRecondition_article_qualityalerts').hide();
	jQuery('#fhRecondition_article_qualityalerts_error').hide();
	jQuery('#fhRecondition_article_qualityalerts_warning').hide();
	
	jQuery.post(
		'index.php?controller=article&task=jsonCheckArticleQuality&type=json',
		{
			"articleId": articleId,
			"batchInCode": batchInCode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhRecondition_data.article.quality = result.data;
				
				if (fhRecondition_data.article.quality.quality_alert == 1) {
					jQuery('#fhRecondition_article_qualityalerts_error_message').html(fhRecondition_data.article.quality.quality_message);
					jQuery('#fhRecondition_article_forceQuality').removeAttr('checked');
					jQuery('#fhRecondition_article_qualityalerts_error').slideDown();
					jQuery('#fhRecondition_article_qualityalerts').slideDown();
				} else if ((fhRecondition_data.article.quality.quality_alert == 0) && (fhRecondition_data.article.quality.quality_message != '')) {
					jQuery('#fhRecondition_article_qualityalerts_warning_message').html(fhRecondition_data.article.quality.quality_message);
					jQuery('#fhRecondition_article_qualityalerts_warning_batchInCodes').html(fhRecondition_data.article.quality.quality_batch_in_codes);
					jQuery('#fhRecondition_article_qualityalerts_warning').slideDown();
					jQuery('#fhRecondition_article_qualityalerts').slideDown();
				}
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}

function fhRecondition_article_togglePackage() {
	
	if (jQuery('#fhRecondition_article_isPackage').is(":checked")) {
		jQuery('#fhRecondition_article_quantity').val(fhRecondition_data.article.package_units);
		jQuery('#fhRecondition_article_quantity').attr('readonly', true);
	} else {
		jQuery('#fhRecondition_article_quantity').attr('readonly', false);
	}
	
}

function fhRecondition_article_batchInCodeChanged() {
	fhRecondition_articleCheckQuality(fhRecondition_data.article.article_id, jQuery('#fhRecondition_article_batchInCode').val());
}

/**
	Cerca di determinare se si tratta di un cartone o no: se si, carica direttamente l'articolo con il codice corrispondente, altrimenti avvia una ricerca
**/
function fhRecondition_barcodeSearch() {
	
	jQuery('#fhRecondition_scan_loading').slideDown();
	
	var barcode = jQuery('#fhRecondition_scan_code').val();
	
	jQuery.post(
		'index.php?controller=recondition&task=jsonCheckBarcode&type=json',
		{
			"barcode": barcode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				if (result.data.article_id > -1) {
					//Se è lo stesso articolo di prima, e non è un cartone, aggiorno direttamente la quantità
					if ((fhRecondition_data.latestArticleId == result.data.article_id) && (result.data.is_package == 0)) {
						var currentQuantity = Number(jQuery('#fhRecondition_article_quantity').val());
						currentQuantity++;
						jQuery('#fhRecondition_article_quantity').val(currentQuantity);
					} else {
						fhRecondition_articleSelect(result.data.article_id, result.data.is_package, result.data.batch_in_code);
					}					
					
				} else {
					fhRecondition_articleSearch(barcode);
				}
				
				jQuery('#fhRecondition_scan_loading').slideUp();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhRecondition_scan_loading').slideUp();
			}
		}
		
	);
	
}

/** PROCESSAMENTO **/
function fhRecondition_process(stockTarget) {

	//Indipendentemente dal processType, verifico che possa andare avanti
	
	//L'articolo deve essere nella lista di carico
	if ((fhRecondition_data.article.chargelist.quantity < 0) && (!jQuery('#fhRecondition_article_forceChargelistInsert').is(":checked"))) {
		alert("L'articolo deve essere inserito nella lista di carico per continuare.");
		return false;
	}
	
	if (Number(jQuery('#fhRecondition_article_quantity').val()) > fhRecondition_data.article.package_units) {
		if (!confirm("Hai inserito un numero di confezioni superiore a quelle contenute in un cartone. Vuoi continuare?")) {
			return false;
		}
	}
	
	if (jQuery('#fhRecondition_article_quantity').val() < 1) {
		alert("Occorre indicare il numero di confezioni da processare");
		return false;
	}
	
	//Non ci devono essere segnalazioni di qualità bloccanti
	if ((fhRecondition_data.article.quality.quality_alert == 1) && (!jQuery('#fhRecondition_article_forceQuality').is(":checked"))) {
		alert("L'articolo ha segnalazioni di qualità. Forzare per continuare.");
		return false;
	}
	
	//OK, provvedo a mandare tutti i dati necessari
	jQuery('#fhRecondition_process').slideUp();
	jQuery('#fhRecondition_progress').slideDown();
	
	if (jQuery('#fhRecondition_article_isPackage').is(":checked")) {
		isPackage = 1;
	} else {
		isPackage = 0;
	}
	
	jQuery.post(
		'index.php?controller=recondition&task=jsonProcess&type=json',
		{
			"stockTarget": stockTarget,
			"chargelistId": fhRecondition_data.chargelist.chargelist_id,
			"articleId": fhRecondition_data.article.article_id,
			"batchInCode": jQuery('#fhRecondition_article_batchInCode').val(),
			"quantity": jQuery('#fhRecondition_article_quantity').val(),
			"isPackage": isPackage
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				alert('Ricondizionamento completato');
				
				//Verifico richieste di aggregazione
				if (result.data.aggregation_available == 1) {
					if (confirm('Si possono aggregare ' + result.data.aggregation_packages + ' cartoni di questo articolo. Procedere?')) {
						fhRecondition_aggregate(fhRecondition_data.article.article_id, result.data.aggregation_packages);
					}
				}
				
				fhRecondition_startProcess();
				jQuery('#fhRecondition_process').slideDown();
				jQuery('#fhRecondition_progress').slideUp();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				
				jQuery('#fhRecondition_process').slideDown();
				jQuery('#fhRecondition_progress').slideUp();
			}
		}
		
	);

}

function fhRecondition_aggregate(articleId, packages) {

	jQuery.post(
		'index.php?controller=recondition&task=jsonAggregatePackage&type=json',
		{
			"articleId": articleId,
			"packages": packages,
			"chargelistId": fhRecondition_data.chargelist.chargelist_id
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if ((result != false) && (result.result >= 0)) {	
				
				alert('Cartone aggregato');
				window.open(result.data.label_url, '_blank');
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}