var fhBuono_data = {};
fhBuono_data.latestArticleId = -1;

/** Chargelist **/
// //
// //function fhBuono_chargelistChoice() {
// //	//modifica apportata da mario
// //	jQuery.post(
// //		'index.php?controller=chargelist&task=jsonLoadChargelist&type=json',
// //		{
// //			"chargelistId": 43 //chargelistId
// //		},
// //		function (data) {
// //			var result = DMResponse.validateJson(data);
// //			
// //			if (result != false) {	
// //				
// //				fhBuono_data.chargelist = result.chargelist;
// //				
// //				jQuery('#fhBuono_chargelist_code').html("000000001");//fhBuono_data.chargelist.chargelist_code
// //				jQuery('#fhBuono_chargelist_date').html("25\/01\/2013");//fhBuono_data.chargelist.chargelist_date_str
// //				jQuery('#fhBuono_chargelist_choice').slideUp();
// //				jQuery('#fhBuono_chargelist_loading').slideUp();
// //				jQuery('#fhBuono_chargelist_loaded').slideDown();
// //				
// //				fhBuono_startProcess();
// //			}
// //		}
// //	);
//	//fine modifica apportata da Mario
//	
//	//var popupChargelistSearch = DMPopup.getInstance({
//	//    name: 'popupChargelistSearch',
//	//    includeCallback: function () {
//	//    	this.openPopup('open', '');
//	//    },
//	//    onSuccess: function (chargelistId) {
//	//    	jQuery('#fhBuono_chargelist_choice').slideUp();
//	//		fhBuono_chargelistSelect(chargelistId);
//	//    }
//	//});
//	
//}

/**
	Attiva la chargelist selezionata
**/
/** Commentata da Mario 
function fhBuono_chargelistSelect(chargelistId) {
	
	if (fhBuono_data.chargelist != undefined) {
		alert('Non puoi modificare la lista di carico di una sessione attiva');
		return false;
	}
	
	jQuery('#fhBuono_chargelist_loading').slideDown();
	jQuery('#fhBuono_chargelist_loaded').slideUp();
	
	//Ottengo i dati della chargelist
	jQuery.post(
		'index.php?controller=chargelist&task=jsonLoadChargelist&type=json',
		{
			"chargelistId": chargelistId
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhBuono_data.chargelist = result.chargelist;
				
				jQuery('#fhBuono_chargelist_code').html(fhBuono_data.chargelist.chargelist_code);
				jQuery('#fhBuono_chargelist_date').html(fhBuono_data.chargelist.chargelist_date_str);
				
				jQuery('#fhBuono_chargelist_loading').slideUp();
				jQuery('#fhBuono_chargelist_loaded').slideDown();
				
				fhBuono_startProcess();
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhBuono_chargelist_loading').slideUp();
			}
		}
		
	);
	
}
**/
/**
	Inizializza il ciclo di lettura/processamento
**/
function fhBuono_startProcess() {
	
	// jQuery('#fhBuono_scan').show();
	jQuery('#fhBuono_process').slideDown();
	jQuery('#fhBuono_article').slideUp();
	jQuery('#fhBuono_scan_code').val("").focus();
	
	fhBuono_data.latestArticleId = -1;
	
}

function fhBuono_finishProcess() {
	
	// jQuery('#fhBuono_scan').show();
	jQuery('#fhBuono_process').slideDown();
	jQuery('#fhBuono_article').slideUp();
	jQuery('#fhBuono_article_quantity').val("");
	jQuery('#fhBuono_scan_code').val("").focus();
	//fhBuono_openUdm();
	
	
	fhBuono_data.latestArticleId = -1;
	
}

/**
	Apre il popup di ricerca articoli
**/
function fhBuono_articleSearch(eanCode) {
	
	
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
	    	fhBuono_articleSelect(articleId, false);
			
			
	    }
	});
	
}

/**
	Seleziona l'articolo e apre la lavorazione su questo
**/
function fhBuono_articleSelect(articleId, batchInCode) {
	
	
	
	if (batchInCode === false) {
		batchInCode = "";
	}
	
	
	jQuery('#fhBuono_article').slideDown();
	jQuery('#fhBuono_article_data').slideUp();
	jQuery('#fhBuono_article_qualityalerts').slideUp();
	jQuery('#fhBuono_article_chargelistalerts').hide();
	jQuery('#fhBuono_article_loading').slideDown();
	
	//Ottengo i dati dell'articolo
	jQuery.post(
		'index.php?controller=article&task=jsonLoadArticle&type=json',
		{
			"articleId": articleId,
			"checkChargelist": 1,
			"chargelistId": 43,  //chargelistId
			"batchInCode": batchInCode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhBuono_data.article = result.article;
				fhBuono_data.latestArticleId = fhBuono_data.article.article_id;
				
				//Carico i dati dell'articolo
				//jQuery('#fhBuono_scan_code').val(fhBuono_data.article.article_code);
				jQuery('#fhBuono_article_articleCode').html(fhBuono_data.article.article_code);
				jQuery('#fhBuono_article_eanCode').html(fhBuono_data.article.ean_code);
				jQuery('#fhBuono_article_packageUnits').html(fhBuono_data.article.package_units);
				jQuery('#fhBuono_article_packageCode').html(fhBuono_data.article.package_code);
				if (fhBuono_data.article.package_description != '') {
					jQuery('#fhBuono_article_packageCode').append(' - ' + fhBuono_data.article.package_description);
				}
				jQuery('#fhBuono_article_name').html(fhBuono_data.article.name);
				jQuery('#fhBuono_article_batchInCode').val(batchInCode);
				jQuery('#fhBuono_article_image').attr('src', fhBuono_data.article.image_url);
				
				//Verifico se mostrare un alert per la mancanza nella lista di carico
				jQuery('#fhBuono_article_forceChargelistInsert').removeAttr('checked');
				if (fhBuono_data.article.chargelist.quantity < 0) { 
					jQuery('#fhBuono_article_chargelistalerts').show();
				}
				
				
				
				//Nel frattempo faccio i controlli di qualità
				fhBuono_articleCheckQuality(articleId, batchInCode);
	
				jQuery('#fhBuono_article_loading').slideUp();
				jQuery('#fhBuono_article_data').slideDown();
				
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhBuono_article_loading').slideUp();
			}
		}
		
	);
	
}


function fhBuono_articleCheckQuality(articleId, batchInCode) {

	jQuery('#fhBuono_article_qualityalerts').hide();
	jQuery('#fhBuono_article_qualityalerts_error').hide();
	jQuery('#fhBuono_article_qualityalerts_warning').hide();
	
	jQuery.post(
		'index.php?controller=article&task=jsonCheckArticleQuality&type=json',
		{
			"articleId": articleId,
			"batchInCode": batchInCode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhBuono_data.article.quality = result.data;
				
				if (fhBuono_data.article.quality.quality_alert == 1) {
					jQuery('#fhBuono_article_qualityalerts_error_message').html(fhBuono_data.article.quality.quality_message);
					jQuery('#fhBuono_article_forceQuality').removeAttr('checked');
					jQuery('#fhBuono_article_qualityalerts_error').slideDown();
					jQuery('#fhBuono_article_qualityalerts').slideDown();
				} else if ((fhBuono_data.article.quality.quality_alert == 0) && (fhBuono_data.article.quality.quality_message != '')) {
					jQuery('#fhBuono_article_qualityalerts_warning_message').html(fhBuono_data.article.quality.quality_message);
					jQuery('#fhBuono_article_qualityalerts_warning_batchInCodes').html(fhBuono_data.article.quality.quality_batch_in_codes);
					jQuery('#fhBuono_article_qualityalerts_warning').slideDown();
					jQuery('#fhBuono_article_qualityalerts').slideDown();
				}
				
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
			}
		}
		
	);
	
}


function fhBuono_article_batchInCodeChanged() {
	fhBuono_articleCheckQuality(fhBuono_data.article.article_id, jQuery('#fhBuono_article_batchInCode').val());
}

/**
	Cerca di determinare se si tratta di un cartone o no: se si, carica direttamente l'articolo con il codice corrispondente, altrimenti avvia una ricerca
**/

function fhBuono_barcodeSearch() {
	
	jQuery.post(
		'index.php?controller=chargelist&task=jsonLoadChargelist&type=json',
		{
			"chargelistId": 43 
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhBuono_data.chargelist = result.chargelist;
				
				jQuery('#fhBuono_chargelist_code').html("000000001");
				jQuery('#fhBuono_chargelist_date').html("01\/01\/2018");
				jQuery('#fhBuono_chargelist_loaded').slideDown();
				
				fhBuono_startProcess();
			}
		}
	);
	
	jQuery('#fhBuono_scan_loading').slideDown();
	
	var barcode = jQuery('#fhBuono_scan_code').val();
	
	jQuery.post(
		'index.php?controller=buono&task=jsonCheckBarcode&type=json',
		{
			"barcode": barcode
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				if (result.data.article_id > -1) {
					fhBuono_articleSelect(result.data.article_id, result.data.batch_in_code);	
				} else {
					fhBuono_articleSearch(barcode);
				}
				
				jQuery('#fhBuono_scan_loading').slideUp();
						
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				jQuery('#fhBuono_scan_loading').slideUp();
			}
		}
		
	);
	
}


/** PROCESSAMENTO **/
function fhBuono_process(stockTarget) {

	//Indipendentemente dal processType, verifico che possa andare avanti
	
	//L'articolo deve essere nella lista di carico
	if ((fhBuono_data.article.chargelist.quantity < 0) && (!jQuery('#fhBuono_article_forceChargelistInsert').is(":checked"))) {
		alert("L'articolo deve essere inserito nella lista di carico per continuare.");
		return false;
	}
	//---- commentato da Mario
	//if (Number(jQuery('#fhBuono_article_quantity').val()) > fhBuono_data.article.package_units) {
	//	if (!confirm("Hai inserito un numero di confezioni superiore a quelle contenute in un cartone. Vuoi continuare?")) {
	//		return false;
	//	}
	//}
	
	if (jQuery('#fhBuono_article_quantity').val() < 1) {
		alert("Occorre indicare il numero di confezioni da processare");
		return false;
	}
	
	//Non ci devono essere segnalazioni di qualità bloccanti
	if ((fhBuono_data.article.quality.quality_alert == 1) && (!jQuery('#fhBuono_article_forceQuality').is(":checked"))) {
		alert("L'articolo ha segnalazioni di qualità. Forzare per continuare.");
		return false;
	}
	
	//OK, provvedo a mandare tutti i dati necessari
	jQuery('#fhBuono_process').slideUp();
	jQuery('#fhBuono_progress').slideDown();
	
	jQuery.post(
		'index.php?controller=buono&task=jsonProcess&type=json',
		{
			"stockTarget": stockTarget,
			"chargelistId": 43,
			"articleId": fhBuono_data.article.article_id,
			"batchInCode": jQuery('#fhBuono_article_batchInCode').val(),
			"quantity": jQuery('#fhBuono_article_quantity').val(),
		},
		function (data) {
			var result = DMResponse.validateJson(data);
			
			if (result != false) {	
				
				fhBuono_finishProcess();
				jQuery('#fhBuono_progress').slideUp();
								
			} else {
				alert("Si è verificato un errore (" + result.result + "): " + result.description);
				
				jQuery('#fhBuono_process').slideDown();
				jQuery('#fhBuono_progress').slideUp();
			}
		}
	);
	
}


function fhBuono_openUdm() {
	
	window.location = 'index.php?controller=logistics';
	
}


